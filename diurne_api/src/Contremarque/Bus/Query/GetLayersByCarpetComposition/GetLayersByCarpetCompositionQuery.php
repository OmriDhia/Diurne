<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetLayersByCarpetComposition;

use App\Common\Bus\Query\Query;

class GetLayersByCarpetCompositionQuery implements Query
{
    public function __construct(
        private readonly int $carpetCompositionId
    ) {
    }

    public function getCarpetCompositionId(): int
    {
        return $this->carpetCompositionId;
    }
}
