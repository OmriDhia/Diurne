<?php

// src/App/Setting/Bus/Command/CollectionGroupPrice/CreateCollectionGroupPriceCommandHandler.php

namespace App\Setting\Bus\Command\CollectionGroupPrice;

use InvalidArgumentException;
use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ValidationException;
use App\Setting\Entity\CollectionGroup;
use App\Setting\Entity\CollectionGroupPrice;
use App\Setting\Repository\CollectionGroupRepository;
use App\Setting\Repository\TarifGroupRepository;
use Doctrine\ORM\EntityManagerInterface;

class CreateCollectionGroupPriceCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly TarifGroupRepository $tarifGroupRepository,
        private readonly CollectionGroupRepository $collectionGroupRepository
    ) {
    }

    public function __invoke(CreateCollectionGroupPriceCommand $command): CollectionGroupPriceResponse
    {
        $tarifGroup = $this->tarifGroupRepository->find($command->tarif_group_id);
        if (!$tarifGroup) {
            throw new InvalidArgumentException('Tarif Group not found');
        }
        $collectionGroup = $this->collectionGroupRepository->find((int) $command->collection_group_id);
        if (!$collectionGroup instanceof CollectionGroup) {
            throw new ValidationException([sprintf('Collection group not fount %d', $command->collection_group_id)]);
        }
        $collectionGroupPrice = new CollectionGroupPrice();
        $collectionGroupPrice->setCollectionGroup($collectionGroup);
        $collectionGroupPrice->setPrice($command->price);
        $collectionGroupPrice->setPriceMax($command->price_max);
        $collectionGroupPrice->setTarifGroup($tarifGroup);

        $this->entityManager->persist($collectionGroupPrice);
        $this->entityManager->flush();

        return new CollectionGroupPriceResponse($collectionGroupPrice);
    }
}
