<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\CarpetMaterial;

use App\Common\Bus\Command\Command;

class DeleteCarpetMaterialCommand implements Command
{
    public function __construct(
        private readonly int $quoteDetailId,
        private readonly int $carpetMaterialId
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
}

