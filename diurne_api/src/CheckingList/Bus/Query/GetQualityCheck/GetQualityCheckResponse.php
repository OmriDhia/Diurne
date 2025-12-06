<?php

namespace App\CheckingList\Bus\Query\GetQualityCheck;

use App\Common\Bus\Query\QueryResponse;
use App\CheckingList\Entity\QualityCheck;

class GetQualityCheckResponse implements QueryResponse
{
    /** @param QualityCheck[] $items */
    public function __construct(private array $items)
    {
    }

    public function toArray(): array
    {
        return array_map(fn(QualityCheck $q) => $q->toArray(), $this->items);
    }
}
