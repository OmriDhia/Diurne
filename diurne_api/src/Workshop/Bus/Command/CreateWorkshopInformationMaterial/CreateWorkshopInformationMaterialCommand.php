<?php

declare(strict_types=1);

namespace App\Workshop\Bus\Command\CreateWorkshopInformationMaterial;

use App\Common\Bus\Command\Command;

class CreateWorkshopInformationMaterialCommand implements Command
{
    public function __construct(
        public readonly int     $materialId,
        public readonly string  $rate,
        public readonly int     $workshopInformationId,
        public readonly ?string $price = null
    )
    {
    }
}
