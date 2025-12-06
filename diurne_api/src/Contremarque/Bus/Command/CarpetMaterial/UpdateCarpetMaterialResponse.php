<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\CarpetMaterial;

use App\Common\Bus\Command\CommandResponse;
use App\Contremarque\Entity\CarpetMaterial;

class UpdateCarpetMaterialResponse implements CommandResponse
{
    public function __construct(private readonly CarpetMaterial $carpetMaterial)
    {
    }

    public function toArray(): array
    {
        return [
            'data' => [
                'id' => $this->carpetMaterial->getId(),
                'material' => $this->carpetMaterial->getMaterial()?->toArray(),
                'rate' => $this->carpetMaterial->getRate(),
                'carpetSpecificationId' => $this->carpetMaterial->getCarpetSpecification()?->getId(),
            ],
        ];
    }
}

