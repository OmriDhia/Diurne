<?php

declare(strict_types=1);

namespace App\Setting\Bus\Command\Material;

use App\Common\Bus\Command\Command;

class CreateMaterialCommand implements Command
{
    public function __construct(
        public string $reference,
        public array $descriptions // ['language_id' => 1, 'label' => 'Description in English']
    ) {
    }
}
