<?php

namespace App\CheckingList\Bus\Query\GetQualityRespect;

use App\Common\Bus\Query\QueryResponse;
use App\CheckingList\Entity\QualityRespect;

class GetQualityRespectResponse implements QueryResponse
{
    /** @param QualityRespect[] $items */
    public function __construct(private array $items)
    {
    }

    public function toArray(): array
    {
        return array_map(fn(QualityRespect $q) => $q->toArray(), $this->items);
    }
}
