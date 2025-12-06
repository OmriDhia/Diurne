<?php

namespace App\CheckingList\Bus\Command\DeleteLayersValidation;

use App\CheckingList\Repository\LayersValidationRepository;
use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use Doctrine\ORM\EntityManagerInterface;

class DeleteLayersValidationHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LayersValidationRepository $repository,
    ) {
    }

    public function __invoke(DeleteLayersValidationCommand $command): DeleteLayersValidationResponse
    {
        $entity = $this->repository->find($command->id);
        if (!$entity) {
            throw new ResourceNotFoundException();
        }

        $this->entityManager->remove($entity);
        $this->entityManager->flush();

        return new DeleteLayersValidationResponse($command->id);
    }
}
