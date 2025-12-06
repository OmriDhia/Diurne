<?php

// src/Setting/Bus/Command/CarpetCollection/CreateCarpetCollectionCommandHandler.php

namespace App\Setting\Bus\Command\CarpetCollection;

use DateTimeImmutable;
use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\DuplicateValidationResourceException;
use App\Common\Exception\ValidationException;
use App\Setting\Entity\CarpetCollection;
use App\Setting\Entity\CollectionGroup;
use App\Setting\Repository\CarpetCollectionRepository;
use App\Setting\Repository\CollectionGroupRepository;
use App\Setting\Repository\PoliceRepository;
use App\User\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class CreateCarpetCollectionCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly CarpetCollectionRepository $repository,
        private readonly CollectionGroupRepository $collectionGroupRepository,
        private readonly PoliceRepository $policeRepository,
        private readonly UserRepository $userRepository
    ) {
    }

    public function __invoke(CreateCarpetCollectionCommand $command)
    {
        $collection = $this->repository->findOneBy(['code' => $command->getCode()]);
        if ($collection instanceof CarpetCollection) {
            throw new DuplicateValidationResourceException();
        }
        $collectionGroup = $this->collectionGroupRepository->find((int) $command->getCollectionGroupId());
        if (!$collectionGroup instanceof CollectionGroup) {
            throw new ValidationException([sprintf('Collection group not fount %d', $command->getCollectionGroupId())]);
        }
        $author = null;
        if (null !== $command->getAuthorId()) {
            $author = $this->userRepository->findById($command->getAuthorId());
            if (null === $author) {
                throw new ValidationException([sprintf('Author not found %d', $command->getAuthorId())]);
            }
        }
        $collection = new CarpetCollection();
        $collection
            ->setReference($command->getReference())
            ->setCode($command->getCode())
            ->setCollectionGroup($collectionGroup)
            ->setShowGrid($command->isShowGrid())
            ->setSpecialShape($command->getSpecialShapeId() ? $this->repository->find($command->getSpecialShapeId()) : null)
            ->setPolice($command->getPoliceId() ? $this->policeRepository->find($command->getPoliceId()) : null)
            ->setImageName($command->getImageName())
            ->setAuthor($author);
        $collection->setCreatedAt(new DateTimeImmutable());
        $collection->setUpdatedAt(new DateTimeImmutable());

        $this->entityManager->persist($collection);
        $this->entityManager->flush();

        return $collection;
    }
}
