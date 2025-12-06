<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetCarpetTypes;

use App\Common\Bus\Query\QueryResponse;

final class GetCarpetTypesResponse implements QueryResponse
{
    public function __construct(public array $carpetTypes)
    {
    }

    public function toArray(): array
    {
        return ['carpet_types' => $this->carpetTypes];
    }
}
