<?php

// src/App/Setting/Bus/Command/CollectionGroup/CreateCollectionGroupCommandHandler.php

namespace App\Setting\Bus\Command\CollectionGroup;

use App\Common\Bus\Command\CommandHandler;
use App\Setting\Entity\CollectionGroup;
use Doctrine\ORM\EntityManagerInterface;

class CreateCollectionGroupCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function __invoke(CreateCollectionGroupCommand $command): CollectionGroupResponse
    {
        $collectionGroup = new CollectionGroup();
        $collectionGroup->setGroupNumber($command->group_number);

        $this->entityManager->persist($collectionGroup);
        $this->entityManager->flush();

        return new CollectionGroupResponse($collectionGroup);
    }
}
