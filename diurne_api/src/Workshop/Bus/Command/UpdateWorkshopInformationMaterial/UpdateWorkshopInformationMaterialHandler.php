<?php

declare(strict_types=1);

namespace App\Workshop\Bus\Command\UpdateWorkshopInformationMaterial;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Setting\Repository\MaterialRepository;
use App\Workshop\Repository\WorkshopInformationMaterialRepository;
use App\Workshop\Repository\WorkshopInformationRepository;
use Doctrine\ORM\EntityManagerInterface;

class UpdateWorkshopInformationMaterialHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface                $entityManager,
        private readonly WorkshopInformationMaterialRepository $workshopInformationMaterialRepository,
        private readonly WorkshopInformationRepository         $workshopInformationRepository,
        private readonly MaterialRepository                    $materialRepository,
    )
    {
    }

    /**
     * @throws ResourceNotFoundException
     */
    public function __invoke(UpdateWorkshopInformationMaterialCommand $command): UpdateWorkshopInformationMaterialResponse
    {
        $workshopInformationMaterial = $this->workshopInformationMaterialRepository->find($command->id);
        if (!$workshopInformationMaterial) {
            throw new ResourceNotFoundException();
        }

        if ($command->materialId !== null) {
            $material = $this->materialRepository->find($command->materialId);
            if (!$material) {
                throw new ResourceNotFoundException();
            }
            $workshopInformationMaterial->setMaterial($material);
        }

        if ($command->rate !== null) {
            $workshopInformationMaterial->setRate($command->rate);
        }

        if ($command->price !== null) {
            $workshopInformationMaterial->setPrice($command->price);
        }

        if ($command->workshopInformationId !== null) {
            $workshopInformation = $this->workshopInformationRepository->find($command->workshopInformationId);
            if (!$workshopInformation) {
                throw new ResourceNotFoundException();
            }
            $workshopInformationMaterial->setWorkshopInformation($workshopInformation);
        }

        $this->entityManager->flush();

        return new UpdateWorkshopInformationMaterialResponse($workshopInformationMaterial);
    }
}
