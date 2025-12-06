<?php

declare(strict_types=1);

namespace App\Workshop\Bus\Command\CreateWorkshopInformationMaterial;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Setting\Repository\MaterialRepository;
use App\Workshop\Entity\WorkshopInformationMaterial;
use App\Workshop\Repository\WorkshopInformationRepository;
use Doctrine\ORM\EntityManagerInterface;

class CreateWorkshopInformationMaterialHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface        $entityManager,
        private readonly WorkshopInformationRepository $workshopInformationRepository,
        private readonly MaterialRepository            $materialRepository,
    )
    {
    }

    /**
     * @throws ResourceNotFoundException
     */
    public function __invoke(CreateWorkshopInformationMaterialCommand $command): WorkshopInformationMaterialResponse
    {
        $workshopInformation = $this->workshopInformationRepository->find($command->workshopInformationId);
        if (!$workshopInformation) {
            throw new ResourceNotFoundException();
        }

        $material = $this->materialRepository->find($command->materialId);
        if (!$material) {
            throw new ResourceNotFoundException();
        }

        $workshopInformationMaterial = new WorkshopInformationMaterial();
        $workshopInformationMaterial->setMaterial($material);
        $workshopInformationMaterial->setRate($command->rate);
        if ($command->price !== null) {
            $workshopInformationMaterial->setPrice($command->price);
        }
        $workshopInformationMaterial->setWorkshopInformation($workshopInformation);

        $this->entityManager->persist($workshopInformationMaterial);
        $this->entityManager->flush();

        return new WorkshopInformationMaterialResponse($workshopInformationMaterial, $workshopInformation);
    }
}
