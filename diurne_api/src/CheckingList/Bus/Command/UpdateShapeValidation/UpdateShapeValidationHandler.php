<?php

namespace App\CheckingList\Bus\Command\UpdateShapeValidation;

use App\CheckingList\Repository\ShapeValidationRepository;
use App\Common\Bus\Command\CommandHandler;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;

class UpdateShapeValidationHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ShapeValidationRepository $repository,
    ) {
    }

    public function __invoke(UpdateShapeValidationCommand $command): UpdateShapeValidationResponse
    {
        $entity = $this->repository->find($command->id);
        if (!$entity) {
            throw new EntityNotFoundException('ShapeValidation not found');
        }

        // Shape validation fields
        if (null !== $command->shapeRelevant) {
            $entity->setShapeRelevant($command->shapeRelevant);
        }
        if (null !== $command->shapeValidation) {
            $entity->setShapeValidation($command->shapeValidation);
        }
        if (null !== $command->shapeSeen) {
            $entity->setShapeSeen($command->shapeSeen);
        }

        // Measurement fields
        if (null !== $command->realWidth) {
            $entity->setRealWidth($command->realWidth);
        }
        if (null !== $command->realLength) {
            $entity->setRealLength($command->realLength);
        }
        if (null !== $command->surface) {
            $entity->setSurface($command->surface);
        }
        if (null !== $command->diagonalA) {
            $entity->setDiagonalA($command->diagonalA);
        }
        if (null !== $command->diagonalB) {
            $entity->setDiagonalB($command->diagonalB);
        }
        if (null !== $command->comment) {
            $entity->setComment($command->comment);
        }

        $this->entityManager->flush();

        return new UpdateShapeValidationResponse($entity);
    }
}
