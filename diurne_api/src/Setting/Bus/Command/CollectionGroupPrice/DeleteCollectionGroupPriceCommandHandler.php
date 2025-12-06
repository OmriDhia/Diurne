<?php

namespace App\Setting\Bus\Command\CollectionGroupPrice;

use RuntimeException;
use Exception;
use App\Common\Bus\Command\CommandHandler;
use App\Setting\Entity\CollectionGroupPrice;
use App\Setting\Repository\CollectionGroupPriceRepository;

class DeleteCollectionGroupPriceCommandHandler implements CommandHandler
{
    public function __construct(private readonly CollectionGroupPriceRepository $collectiongrouppriceRepository) {}

    public function __invoke(DeleteCollectionGroupPriceCommand $command): CollectionGroupPriceResponse
    {
        $collectiongroupprice = $this->collectiongrouppriceRepository->find($command->id);
        if (!$collectiongroupprice) {
            throw new RuntimeException('CollectionGroupPrice not found', 404);
        }

        try {
            $this->collectiongrouppriceRepository->remove($collectiongroupprice);
            $this->collectiongrouppriceRepository->flush();
        } catch (Exception $e) {
            throw new RuntimeException('Failed to delete collectiongroupprice: ' . $e->getMessage(), 0, $e);
        }

        return new CollectionGroupPriceResponse($collectiongroupprice);
    }
}
