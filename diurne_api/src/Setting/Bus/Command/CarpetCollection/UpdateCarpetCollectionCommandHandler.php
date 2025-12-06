<?php

namespace App\Setting\Bus\Command\CarpetCollection;

use DateTimeImmutable;
use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Common\Exception\ValidationException;
use App\Setting\Entity\CarpetCollection;
use App\Setting\Entity\CollectionGroup;
use App\Setting\Entity\Police;
use App\Setting\Entity\SpecialShape;
use App\Setting\Repository\CarpetCollectionRepository;
use App\Setting\Repository\CollectionGroupRepository;
use App\Setting\Repository\PoliceRepository;
use App\Setting\Repository\SpecialShapeRepository;
use App\User\Entity\User;
use App\User\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UpdateCarpetCollectionCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly CarpetCollectionRepository $repository,
        private readonly CollectionGroupRepository $collectionGroupRepository,
        private readonly SpecialShapeRepository $specialShapeRepository,
        private readonly PoliceRepository $policeRepository,
        private readonly UserRepository $userRepository,
    ) {
    }

    public function __invoke(UpdateCarpetCollectionCommand $command): CarpetCollection
    {
        $collection = $this->repository->find($command->getId());

        if (!$collection instanceof CarpetCollection) {
            throw new ResourceNotFoundException();
        }

        $collectionGroup = $this->collectionGroupRepository->find($command->getCollectionGroupId());
        if (!$collectionGroup instanceof CollectionGroup) {
            throw new ValidationException([sprintf('Collection group not found %d', $command->getCollectionGroupId())]);
        }

        $author = null;
        if (null !== $command->getAuthorId()) {
            $author = $this->userRepository->findById($command->getAuthorId());
            if (!$author instanceof User) {
                throw new ValidationException([sprintf('Author not found %d', $command->getAuthorId())]);
            }
        }

        $specialShape = null;
        if (null !== $command->getSpecialShapeId()) {
            $specialShape = $this->specialShapeRepository->find($command->getSpecialShapeId());
            if (!$specialShape instanceof SpecialShape) {
                throw new ValidationException([sprintf('Special shape not found %d', $command->getSpecialShapeId())]);
            }
        }

        $police = null;
        if (null !== $command->getPoliceId()) {
            $police = $this->policeRepository->find($command->getPoliceId());
            if (!$police instanceof Police) {
                throw new ValidationException([sprintf('Police not found %d', $command->getPoliceId())]);
            }
        }

        $collection
            ->setReference($command->getReference())
            ->setCode($command->getCode())
            ->setCollectionGroup($collectionGroup)
            ->setShowGrid($command->isShowGrid())
            ->setSpecialShape($specialShape)
            ->setPolice($police)
            ->setImageName($command->getImageName())
            ->setAuthor($author)
            ->setUpdatedAt(new DateTimeImmutable());

        $this->entityManager->flush();

        return $collection;
    }
}
