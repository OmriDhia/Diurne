<?php

declare(strict_types=1);

namespace App\Workshop\Bus\Command\DeleteWorkshopInformationMaterial;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Workshop\Repository\WorkshopInformationMaterialRepository;
use Doctrine\ORM\EntityManagerInterface;

class DeleteWorkshopInformationMaterialHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly WorkshopInformationMaterialRepository $workshopInformationMaterialRepository,
    ) {
    }

    /**
     * @throws ResourceNotFoundException
     */
    public function __invoke(DeleteWorkshopInformationMaterialCommand $command): DeleteWorkshopInformationMaterialResponse
    {
        $workshopInformationMaterial = $this->workshopInformationMaterialRepository->find($command->id);
        if (!$workshopInformationMaterial) {
            throw new ResourceNotFoundException();
        }

        $this->entityManager->remove($workshopInformationMaterial);
        $this->entityManager->flush();

        return new DeleteWorkshopInformationMaterialResponse($command->id);
    }
}
