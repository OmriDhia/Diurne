<?php

namespace App\Setting\Bus\Command\Quality;

use App\Common\Bus\Command\Command;

class UpdateQualityCommand implements Command
{
    public function __construct(
        public int $id,
        public ?string $name,
        public ?string $weight,
        public ?string $velvet_standard,
        public ?array $description
    ) {
    }
}
