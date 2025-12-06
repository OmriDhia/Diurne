<?php

namespace App\Contremarque\Bus\Command\CreateDesignerComposition;

use App\Common\Bus\Command\Command;

class CreateDesignerCompositionCommand implements Command
{
    public function __construct(
        private readonly int $materialId,
        private readonly int $carpetSpecificationId,
        private readonly string $rate
    ) {
    }

    public function getMaterialId(): int
    {
        return $this->materialId;
    }

    public function getCarpetSpecificationId(): int
    {
        return $this->carpetSpecificationId;
    }

    public function getRate(): string
    {
        return $this->rate;
    }
}
