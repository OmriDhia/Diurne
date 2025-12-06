<?php

// src/App/Setting/Bus/Command/CollectionGroup/CollectionGroupResponse.php

namespace App\Setting\Bus\Command\CollectionGroup;

use App\Common\Bus\Command\CommandResponse;
use App\Setting\Entity\CollectionGroup;

class CollectionGroupResponse implements CommandResponse
{
    public function __construct(private readonly CollectionGroup $collectionGroup)
    {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->collectionGroup->getId(),
            'group_number' => $this->collectionGroup->getGroupNumber(),
        ];
    }

    public function getCollectionGroup(): CollectionGroup
    {
        return $this->collectionGroup;
    }
}
