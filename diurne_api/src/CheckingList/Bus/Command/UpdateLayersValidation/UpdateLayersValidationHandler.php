<?php

namespace App\CheckingList\Bus\Command\UpdateLayersValidation;

use App\CheckingList\Repository\LayersValidationRepository;
use App\Common\Bus\Command\CommandHandler;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;

class UpdateLayersValidationHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LayersValidationRepository $repository,
    ) {
    }

    public function __invoke(UpdateLayersValidationCommand $command): UpdateLayersValidationResponse
    {
        $entity = $this->repository->find($command->id);
        if (!$entity) {
            throw new EntityNotFoundException('LayersValidation not found');
        }

        if (null !== $command->layerComment) {
            $entity->setLayerComment($command->layerComment);
        }
        if (null !== $command->layerValidation) {
            $entity->setLayerValidation($command->layerValidation);
        }

        $this->entityManager->flush();

        return new UpdateLayersValidationResponse($entity);
    }
}
