<?php

// src/App/Setting/Bus/Command/CollectionGroupPrice/CollectionGroupPriceResponse.php

namespace App\Setting\Bus\Command\CollectionGroupPrice;

use App\Common\Bus\Command\CommandResponse;
use App\Setting\Entity\CollectionGroupPrice;

class CollectionGroupPriceResponse implements CommandResponse
{
    public function __construct(private readonly CollectionGroupPrice $collectionGroupPrice)
    {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->collectionGroupPrice->getId(),
            'group_number' => $this->collectionGroupPrice->getCollectionGroup()->getGroupNumber(),
            'price' => $this->collectionGroupPrice->getPrice(),
            'price_max' => $this->collectionGroupPrice->getPriceMax(),
            'tarif_group_id' => $this->collectionGroupPrice->getTarifGroup() ? $this->collectionGroupPrice->getTarifGroup()->getId() : null,
            // Add more fields as needed
        ];
    }

    public function getCollectionGroupPrice(): CollectionGroupPrice
    {
        return $this->collectionGroupPrice;
    }
}
