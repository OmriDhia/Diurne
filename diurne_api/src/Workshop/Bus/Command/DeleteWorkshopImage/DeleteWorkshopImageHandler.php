<?php

namespace App\Workshop\Bus\Command\DeleteWorkshopImage;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Workshop\Repository\WorkshopImageRepository;
use Doctrine\ORM\EntityManagerInterface;

class DeleteWorkshopImageHandler implements CommandHandler
{
    /**
     * @param EntityManagerInterface $entityManager
     * @param WorkshopImageRepository $workshopImageRepository
     */
    public function __construct(
        private EntityManagerInterface  $entityManager,
        private WorkshopImageRepository $workshopImageRepository
    )
    {
    }

    /**
     * @param DeleteWorkshopImageCommand $command
     * @return void
     * @throws ResourceNotFoundException
     */
    public function __invoke(DeleteWorkshopImageCommand $command): DeleteWorkshopImageResponse
    {
        $workshopImage = $this->workshopImageRepository->find($command->id);

        if (!$workshopImage) {
            throw new ResourceNotFoundException ();
        }
        $this->entityManager->remove($workshopImage);
        $this->entityManager->flush();
        return new DeleteWorkshopImageResponse($command->id);
    }
}