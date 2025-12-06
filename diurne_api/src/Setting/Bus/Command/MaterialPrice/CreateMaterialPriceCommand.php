<?php

namespace App\Setting\Bus\Command\MaterialPrice;

use App\Common\Bus\Command\Command;

class CreateMaterialPriceCommand implements Command
{
    public function __construct(
        public readonly int $materialId,
        public readonly ?float $publicPrice,
        public readonly ?float $bigProjectPrice
    ) {
    }

    public function getMaterialId(): int
    {
        return $this->materialId;
    }

    public function getPublicPrice(): ?float
    {
        return $this->publicPrice;
    }

    public function getBigProjectPrice(): ?float
    {
        return $this->bigProjectPrice;
    }
}
