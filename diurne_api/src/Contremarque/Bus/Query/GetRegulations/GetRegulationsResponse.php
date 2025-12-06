<?php

namespace App\Contremarque\Bus\Query\GetRegulations;

use App\Common\Bus\Query\QueryResponse;
use App\Contremarque\Entity\Regulation;

class GetRegulationsResponse implements QueryResponse
{
    /** @param Regulation[] $items */
    public function __construct(private array $items)
    {
    }

    public function toArray(): array
    {
        return array_map(fn(Regulation $r) => $r->toArray(), $this->items);
    }
}
