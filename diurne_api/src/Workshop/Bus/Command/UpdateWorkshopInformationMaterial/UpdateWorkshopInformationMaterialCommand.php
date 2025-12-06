<?php

declare(strict_types=1);

namespace App\Workshop\Bus\Command\UpdateWorkshopInformationMaterial;

use App\Common\Bus\Command\Command;

class UpdateWorkshopInformationMaterialCommand implements Command
{
    public function __construct(
        public readonly int     $id,
        public readonly ?int    $materialId = null,
        public readonly ?string $rate = null,
        public readonly ?string $price = null,
        public readonly ?int    $workshopInformationId = null,
    )
    {
    }
}
