<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\CarpetDesignOrder;

use DateTimeImmutable;
use App\Common\Bus\Command\CommandHandler;
use App\Contremarque\Entity\CarpetDesignOrder;
use App\Contremarque\Repository\CarpetDesignOrderRepository;
use App\Contremarque\Repository\CarpetStatusRepository;
use App\Contremarque\Repository\LocationRepository;
use App\Contremarque\Repository\ProjectDiRepository;

class CreateCarpetDesignOrderCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly CarpetDesignOrderRepository $carpetDesignOrderRepository,
        private readonly LocationRepository          $locationRepository,
        private readonly ProjectDiRepository         $projectDiRepository,
        private readonly CarpetStatusRepository      $carpetStatusRepository
    ) {}

    public function __invoke(CreateCarpetDesignOrderCommand $command): CarpetDesignOrderResponse
    {
        $projectDi = $this->projectDiRepository->find((int)$command->getProjectDiId());
        $location = $this->locationRepository->find((int)$command->getLocationId());
        $carpetDesignOrder = new CarpetDesignOrder();
        $carpetDesignOrder->setProjectDi($projectDi);
        $carpetDesignOrder->setLocation($location);
        $carpetDesignOrder->setModelName($command->getModelName());
        $carpetDesignOrder->setVariation($command->getVariation());
        $carpetDesignOrder->setJpeg($command->getJpeg());
        $carpetDesignOrder->setImpression($command->getImpression());
        $carpetDesignOrder->setImpressionBarreDeLaine($command->getImpressionBarreDeLaine());
        if (null !== $command->getStatusId()) {
            $status = $this->carpetStatusRepository->find((int)$command->getStatusId());
            $TransmittedStatus = $this->carpetStatusRepository->getStatusByName('Transmis');
            if ($TransmittedStatus === $status) {
                $carpetDesignOrder->setTransmitionDate(new DateTimeImmutable());
                $carpetDesignOrder->setStatus($status);
            } else {
                $carpetDesignOrder->setTransmitionDate(null);
            }
        }
        if ($carpetDesignOrder->getStatus() === null) {
            $carpetDesignOrder->setStatus($this->carpetStatusRepository->getStatusByName('Non transmis'));
        }
        // Handle designer assignments (if needed)

        $this->carpetDesignOrderRepository->persist($carpetDesignOrder);
        $this->carpetDesignOrderRepository->flush();

        return new CarpetDesignOrderResponse($carpetDesignOrder);
    }
}
