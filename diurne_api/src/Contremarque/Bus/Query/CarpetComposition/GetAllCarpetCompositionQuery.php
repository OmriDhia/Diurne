<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\CarpetComposition;

use App\Common\Bus\Query\Query;

class GetAllCarpetCompositionQuery implements Query
{
    public function __construct(
        public int $carpetSpecificationId
    ) {
    }

    public function carpetSpecificationId(): int
    {
        return $this->carpetSpecificationId;
    }
}
