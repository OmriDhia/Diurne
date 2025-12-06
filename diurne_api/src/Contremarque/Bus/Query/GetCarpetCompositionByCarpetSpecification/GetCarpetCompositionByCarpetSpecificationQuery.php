<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetCarpetCompositionByCarpetSpecification;

use App\Common\Bus\Query\Query;

class GetCarpetCompositionByCarpetSpecificationQuery implements Query
{
    public function __construct(
        private readonly int $carpetSpecificationId
    ) {
    }

    public function getCarpetSpecificationId(): int
    {
        return $this->carpetSpecificationId;
    }
}
