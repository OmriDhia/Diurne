<?php

namespace App\CheckingList\Bus\Command\DeleteShapeValidation;

use App\CheckingList\Repository\ShapeValidationRepository;
use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use Doctrine\ORM\EntityManagerInterface;

class DeleteShapeValidationHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ShapeValidationRepository $repository,
    ) {
    }

    public function __invoke(DeleteShapeValidationCommand $command): DeleteShapeValidationResponse
    {
        $entity = $this->repository->find($command->id);
        if (!$entity) {
            throw new ResourceNotFoundException();
        }

        $this->entityManager->remove($entity);
        $this->entityManager->flush();

        return new DeleteShapeValidationResponse($command->id);
    }
}
