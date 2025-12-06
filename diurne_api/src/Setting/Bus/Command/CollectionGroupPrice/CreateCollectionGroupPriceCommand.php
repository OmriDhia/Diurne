<?php

// src/App/Setting/Bus/Command/CollectionGroup/CreateCollectionGroupCommand.php

namespace App\Setting\Bus\Command\CollectionGroupPrice;

use App\Common\Bus\Command\Command;

class CreateCollectionGroupPriceCommand implements Command
{
    public function __construct(
        public int $collection_group_id,
        public string $price,
        public ?string $price_max,
        public int $tarif_group_id
    ) {
    }
}
