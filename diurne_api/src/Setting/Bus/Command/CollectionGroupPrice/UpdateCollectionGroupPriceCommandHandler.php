<?php

namespace App\Setting\Bus\Command\CollectionGroupPrice;

use RuntimeException;
use Exception;
use App\Common\Bus\Command\CommandHandler;
use App\Setting\Entity\CollectionGroupPrice;
use App\Setting\Repository\CollectionGroupPriceRepository;
use App\Setting\Repository\CollectionGroupRepository;
use App\Setting\Repository\TarifGroupRepository;
use App\Setting\Bus\Command\CollectionGroupPrice\CollectionGroupPriceResponse;

class UpdateCollectionGroupPriceCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly CollectionGroupPriceRepository $collectionGroupPriceRepository,
        private readonly CollectionGroupRepository $collectionGroupRepository,
        private readonly TarifGroupRepository $tarifGroupRepository
    ) {}

    public function __invoke(UpdateCollectionGroupPriceCommand $command): CollectionGroupPriceResponse
    {
        $collectionGroupPrice = $this->collectionGroupPriceRepository->find($command->id);
        if (!$collectionGroupPrice) {
            throw new RuntimeException('CollectionGroupPrice not found', 404);
        }

        $collectionGroup = $this->collectionGroupRepository->find($command->collection_group_id);
        if (!$collectionGroup) {
            throw new RuntimeException('CollectionGroup not found', 404);
        }

        $tarifGroup = $this->tarifGroupRepository->find($command->tarif_group_id);
        if (!$tarifGroup) {
            throw new RuntimeException('TarifGroup not found', 404);
        }

        $collectionGroupPrice->setCollectionGroup($collectionGroup);
        $collectionGroupPrice->setPrice((string) $command->price);
        $collectionGroupPrice->setPriceMax((string) $command->price_max);
        $collectionGroupPrice->setTarifGroup($tarifGroup);

        try {
            $this->collectionGroupPriceRepository->flush();
        } catch (Exception $e) {
            throw new RuntimeException('Failed to update collection group price: ' . $e->getMessage(), 0, $e);
        }

        return new CollectionGroupPriceResponse($collectionGroupPrice);
    }
}
