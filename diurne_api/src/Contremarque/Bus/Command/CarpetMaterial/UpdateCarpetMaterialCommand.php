<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\CarpetMaterial;

use App\Common\Bus\Command\Command;

class UpdateCarpetMaterialCommand implements Command
{
    public function __construct(
        private readonly int     $quoteDetailId,
        private readonly int     $carpetMaterialId,
        private readonly ?string $rate = null,
        private readonly ?int    $materialId = null
    )
    {
    }

    public function getQuoteDetailId(): int
    {
        return $this->quoteDetailId;
    }

    public function getCarpetMaterialId(): int
    {
        return $this->carpetMaterialId;
    }

    public function getRate(): ?string
    {
        return $this->rate;
    }

    public function getMaterialId(): ?int
    {
        return $this->materialId;
    }
}

