<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\UpdateDesignerAssignment;

use DateTime;
use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ValidationException;
use App\Contremarque\Repository\CarpetDesignOrderRepository;
use App\Contremarque\Repository\CarpetStatusRepository;
use App\Contremarque\Repository\DesignerAssignmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UpdateDesignerAssignmentCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly DesignerAssignmentRepository $designerAssignmentRepository,
        private readonly CarpetDesignOrderRepository $carpetDesignOrderRepository,
        private readonly CarpetStatusRepository $carpetStatusRepository
    ) {
    }

    public function __invoke(UpdateDesignerAssignmentCommand $command): UpdateDesignerAssignmentResponse
    {
        $designerAssignment = $this->designerAssignmentRepository->find($command->id);
        if (!$designerAssignment) {
            throw new NotFoundHttpException('DesignerAssignment not found.');
        }

        if (null !== $command->dateFrom) {
            $dateFrom = is_string($command->dateFrom) ? new DateTime($command->dateFrom) : $command->dateFrom;
            $designerAssignment->setDateFrom($dateFrom);
        }
        $dateTo = (null !== $command->dateTo && is_string($command->dateTo)) ? new DateTime($command->dateTo) : $command->dateTo;
        if (null !== $command->dateTo) {
            $designerAssignment->setDateTo($dateTo);
            if (new DateTime('2100-01-01 00:00:00') == $dateTo) {
                $designerAssignment->setDateTo('');
            }
        }

        if ((bool) $command->inProgress) {
            $designerAssignment->setInProgress($command->inProgress);
            $carpetDesignOrder = $designerAssignment->getCarpetDesignOrder();
            $status = $this->carpetStatusRepository->getStatusByName('En cours');
            $carpetDesignOrder->setStatus($status);
            $this->entityManager->persist($carpetDesignOrder);
            $this->entityManager->flush();
        } else {
            $designerAssignment->setInProgress($command->inProgress);
        }

        if ((bool) $command->stopped) {
            $designerAssignment->setStopped($command->stopped);
            $carpetDesignOrder = $designerAssignment->getCarpetDesignOrder();
            $status = $this->carpetStatusRepository->getStatusByName('En pause');
            $carpetDesignOrder->setStatus($status);
            $this->entityManager->persist($carpetDesignOrder);
            $this->entityManager->flush();
        } else {
            $designerAssignment->setStopped($command->stopped);
        }

        if ((bool) $command->done) {
            $designerAssignment->setDone($command->done);
            $carpetDesignOrder = $designerAssignment->getCarpetDesignOrder();
            $hasVignette = $this->carpetDesignOrderRepository->hasVignetteImage($carpetDesignOrder->getId());
            if ($hasVignette) {
                $status = $this->carpetStatusRepository->getStatusByName('Fini');
                $carpetDesignOrder->setStatus($status);
                $this->entityManager->persist($carpetDesignOrder);
                $this->entityManager->flush();
            } else {
                throw new ValidationException(['At least one vignette is required']);
            }
        } else {
            $designerAssignment->setDone($command->done);
        }
        $this->entityManager->persist($designerAssignment);
        $this->entityManager->flush();

        return new UpdateDesignerAssignmentResponse($designerAssignment);
    }
}
