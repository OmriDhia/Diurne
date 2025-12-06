<?php

// src/App/Setting/Bus/Command/CollectionGroup/CreateCollectionGroupCommand.php

namespace App\Setting\Bus\Command\CollectionGroup;

use App\Common\Bus\Command\Command;

class CreateCollectionGroupCommand implements Command
{
    public function __construct(
        public int $group_number
    ) {
    }
}
