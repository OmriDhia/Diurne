<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\ProjectDi;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ValidationException;
use App\Contremarque\Entity\ProjectDi;
use App\Contremarque\Repository\ContremarqueRepository;
use App\Contremarque\Repository\ProjectDiRepository;
use App\Contremarque\Repository\CarpetStatusRepository;
use App\Contremarque\Repository\UnitOfMeasurementRepository;
use Doctrine\ORM\EntityManagerInterface;

class UpdateProjectDiCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ProjectDiRepository $projectDiRepository,
        private readonly UnitOfMeasurementRepository $unitOfMeasurementRepository,
        private readonly ContremarqueRepository $contremarqueRepository,
        private readonly CarpetStatusRepository $carpetStatusRepository,
    ) {
    }

    public function __invoke(UpdateProjectDiCommand $command): ProjectDi
    {
        $projectDi = $this->projectDiRepository->find($command->id);

        if (!$projectDi) {
            throw new ValidationException(['ProjectDi not found']);
        }

        if (null !== $command->format) {
            $projectDi->setFormat($command->format);
        }

        if (null !== $command->deadline) {
            $projectDi->setDeadline($command->deadline);
        }

        if (null !== $command->transmitted_to_studio) {
            $projectDi->setTransmittedToStudio($command->transmitted_to_studio);
            if ($command->transmitted_to_studio) {
                $status = $this->carpetStatusRepository->find(2);
                if ($status) {
                    foreach ($projectDi->getCarpetDesignOrders() as $carpetDesignOrder) {
                        $carpetDesignOrder->setStatus($status);
                    }
                }
            }
        }

        if (null !== $command->transmition_date) {
            $projectDi->setTransmitionDate($command->transmition_date);
        }

        if (null !== $command->unit_id) {
            $unit = $this->unitOfMeasurementRepository->find($command->unit_id);
            if (null === $unit) {
                throw new ValidationException(['Unit of measurement not found']);
            }
            $projectDi->setUnit($unit);
        }

        if (null !== $command->contremarque_id) {
            $contremarque = $this->contremarqueRepository->find($command->contremarque_id);
            if (null === $contremarque) {
                throw new ValidationException(['Contremarque not found']);
            }
            $projectDi->setContremarque($contremarque);
        }

        $this->entityManager->persist($projectDi);
        $this->entityManager->flush();

        return $projectDi;
    }
}
