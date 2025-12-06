<?php

namespace App\Setting\Bus\Command\Material;

use App\Common\Bus\Command\Command;

class UpdateMaterialCommand implements Command
{
    public function __construct(
        public int $id,
        public ?string $reference,
        public ?array $descriptions
    ) {
    }
}
