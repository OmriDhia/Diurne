<?php

namespace App\Contremarque\Bus\Command\ImageCommand;

use App\Common\Bus\Command\CommandHandler;
use App\Contremarque\Entity\ImageCommand\ImageCommand;
use App\Contremarque\Repository\CarpetDesignOrderRepository;
use App\Contremarque\Repository\CarpetStatusRepository;
use App\Contremarque\Repository\ImageCommandRepository;
use App\Contremarque\Repository\SampleRepository;
use DateTimeImmutable;

class CreateImageCommandCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly ImageCommandRepository      $imageCommandRepository,
        private readonly CarpetDesignOrderRepository $carpetDesignOrderRepository,
        private readonly SampleRepository            $sampleRepository,
        private readonly CarpetStatusRepository      $carpetStatusRepository
    )
    {
    }

    public function __invoke(CreateImageCommandCommand $command): ImageCommandResponse
    {
        $imageCommand = new ImageCommand();
        if (!empty($command->getObjectId())) {

            if ($command->getObjectType() === 'CarpetDesignOrder') {
                $carpetDesignOrder = $this->carpetDesignOrderRepository->find($command->getObjectId());
                if (null === $carpetDesignOrder) {
                    throw new \Exception('CarpetDesignOrder not found');
                }
                $imageCommand->setCarpetDesignOrder($carpetDesignOrder);
            }
            if ($command->getObjectType() === 'Sample') {
                $sample = $this->sampleRepository->find($command->getObjectType());

                if (null === $sample) {
                    throw new \Exception('Sample not found');
                }

                $imageCommand->setSample($sample);
            }
        }
        $imageCommand->setCommandNumber($command->getCommandNumber());
        $imageCommand->setCommercialComment($command->getCommercialComment());
        $imageCommand->setAdvComment($command->getAdvComment());
        $imageCommand->setStudioComment($command->getStudioComment());
        $carpetDesignOrder = $this->carpetDesignOrderRepository->find($command->getObjectId());
        if (null !== $command->getStatusId()) {
            $status = $this->carpetStatusRepository->find((int)$command->getStatusId());
            $TransmittedStatus = $this->carpetStatusRepository->getStatusByName('Fini');
            $ToAdvStatus = $this->carpetStatusRepository->getStatusByName("Transmis à l'ADV");
            if ($TransmittedStatus === $status) {
                $imageCommand->setTransmitionDate(new DateTimeImmutable());
                // $imageCommand->setStatus($status);
                $carpetDesignOrder->setStatus($ToAdvStatus);
            } else {
                $imageCommand->setTransmitionDate(null);
            }
        }

        // Définir le commercialId de la demande d'image
        if ($command->getObjectType() === 'CarpetDesignOrder') {
            $carpetDesignOrder = $this->carpetDesignOrderRepository->find($command->getObjectId());
            if ($carpetDesignOrder) {
                // Récupérer le commercial via la contremarque
                $projectDi = $carpetDesignOrder->getProjectDi();
                if ($projectDi) {
                    $contremarque = $projectDi->getContremarque();
                    if ($contremarque) {
                        $contremarqueCommercialId = $contremarque->getCommercialId();
                        if ($contremarqueCommercialId) {
                            $imageCommand->setCommercialId($contremarqueCommercialId);
                        }
                    }
                }
            }
        }
        
        $this->imageCommandRepository->persist($imageCommand);
        $this->imageCommandRepository->flush();

        return new ImageCommandResponse($imageCommand);
    }
}
