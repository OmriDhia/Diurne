<?php

namespace App\Contremarque\Bus\Command\CreateDesignerAssignment;

use RuntimeException;
use DateTimeImmutable;
use DateTime;
use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ValidationException;
use App\Contremarque\Entity\DesignerAssignment;
use App\Contremarque\Repository\CarpetDesignOrderRepository;
use App\Contremarque\Repository\CarpetStatusRepository;
use App\Contremarque\Repository\ContremarqueRepository;
use App\User\Repository\ProfileRepository;
use App\User\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

// Add this

class CreateDesignerAssignmentCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface      $entityManager,
        private readonly UserRepository              $userRepository,
        private readonly CarpetDesignOrderRepository $carpetDesignOrderRepository,
        private readonly ContremarqueRepository      $contremarqueRepository,
        private readonly CarpetStatusRepository      $carpetStatusRepository,
        private readonly RequestStack                $requestStack,
        private readonly ProfileRepository           $profileRepository
    )
    {
    }

    public function __invoke(CreateDesignerAssignmentCommand $command): CreateDesignerAssignmentResponse
    {
        $session = $this->requestStack->getSession();
        if (!$session) {
            throw new RuntimeException('Session not found.');
        }
        $user = $session->get('user');
        if (!$user) {
            throw new RuntimeException('User not found in session.');
        }
        $profile = $this->profileRepository->find((int)$user->getProfile()->getId());
        try {
            if (!in_array($profile->getName(), ['Designer manager', 'Super admin'])) {
                throw new ValidationException(['Unautorized to assign designer']);
            }
        } catch (ValidationException $e) {
            error_log('ValidationException caught: ' . $e->getMessage());
            throw $e;
        }
        $carpetDesignOrder = $this->carpetDesignOrderRepository->find($command->carpetDesignOrderId);
        if (!$carpetDesignOrder) {
            throw new NotFoundHttpException('CarpetDesignOrder not found.');
        }

        $designer = $this->userRepository->find($command->designerId);
        if (!$designer) {
            throw new NotFoundHttpException('Designer not found.');
        }

        $designerAssignment = new DesignerAssignment();
        $designerAssignment->setDesigner($designer);
        $designerAssignment->setDateFrom(new DateTimeImmutable());
        $dateTo = (null !== $command->dateTo && is_string($command->dateTo)) ? new DateTime($command->dateTo) : $command->dateTo;
        if (null !== $command->dateTo) {
            $designerAssignment->setDateTo($dateTo);
            if (new DateTime('2100-01-01 00:00:00') == $dateTo) {
                $designerAssignment->setDateTo('');
            }
        }
        $designerAssignment->setInProgress(false);
        $designerAssignment->setStopped(false);
        $designerAssignment->setDone(false);
        $designerAssignment->setCarpetDesignOrder($carpetDesignOrder);

        $this->entityManager->persist($designerAssignment);
        $this->entityManager->flush();
        $carpetDesignOrder = $designerAssignment->getCarpetDesignOrder();
        if ($designer->getId() === $user->getId()) {
            $status = $this->carpetStatusRepository->getStatusByName('En cours');
        } else {
            $status = $this->carpetStatusRepository->getStatusByName('Attribué');
        }

        $carpetDesignOrder->setStatus($status);
        $this->entityManager->persist($carpetDesignOrder);
        $this->entityManager->flush();

        return new CreateDesignerAssignmentResponse($designerAssignment);
    }
}
