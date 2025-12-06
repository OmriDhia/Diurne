<?php

namespace App\CheckingList\Bus\Command\CreateShapeValidation;

use App\CheckingList\Entity\ShapeValidation;
use App\CheckingList\Repository\ShapeValidationRepository;
use App\CheckingList\Repository\CheckingListRepository;
use App\Common\Bus\Command\CommandHandler;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;

class CreateShapeValidationHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ShapeValidationRepository $repository,
        private readonly CheckingListRepository $checkingListRepository,
    ) {
    }

    public function __invoke(CreateShapeValidationCommand $command): CreateShapeValidationResponse
    {
        $checkingList = $this->checkingListRepository->find($command->checkingListId);
        if (!$checkingList) {
            throw new EntityNotFoundException('CheckingList not found');
        }

        $entity = new ShapeValidation();
        $entity->setCheckingList($checkingList);
        $entity->setShapeValidation($command->shapeValidation);
        $entity->setRealWidth($command->realWidth);
        $entity->setRealLength($command->realLength);
        $entity->setSurface($command->surface);
        $entity->setDiagonalA($command->diagonalA);
        $entity->setDiagonalB($command->diagonalB);
        $entity->setComment($command->comment);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return new CreateShapeValidationResponse($entity);
    }
}
