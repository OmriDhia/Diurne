<?php

namespace App\CheckingList\Bus\Command\CreateLayersValidation;

use App\CheckingList\Entity\LayersValidation;
use App\CheckingList\Repository\LayersValidationRepository;
use App\CheckingList\Repository\CheckingListRepository;
use App\Common\Bus\Command\CommandHandler;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;

class CreateLayersValidationHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LayersValidationRepository $repository,
        private readonly CheckingListRepository $checkingListRepository,
    ) {
    }

    public function __invoke(CreateLayersValidationCommand $command): CreateLayersValidationResponse
    {
        $checkingList = $this->checkingListRepository->find($command->checkingListId);
        if (!$checkingList) {
            throw new EntityNotFoundException('CheckingList not found');
        }

        $entity = new LayersValidation();
        $entity->setCheckingList($checkingList);
        $entity->setLayerComment($command->layerComment);
        $entity->setLayerValidation($command->layerValidation);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return new CreateLayersValidationResponse($entity);
    }
}
