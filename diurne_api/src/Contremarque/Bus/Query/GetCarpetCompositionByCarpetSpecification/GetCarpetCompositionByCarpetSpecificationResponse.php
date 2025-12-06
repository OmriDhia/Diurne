<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetCarpetCompositionByCarpetSpecification;

use App\Common\Bus\Query\QueryResponse;

class GetCarpetCompositionByCarpetSpecificationResponse implements QueryResponse
{
    public function __construct(private readonly array $carpetComposition)
    {
    }

    public function toArray(): array
    {
        return $this->carpetComposition;
    }
}
