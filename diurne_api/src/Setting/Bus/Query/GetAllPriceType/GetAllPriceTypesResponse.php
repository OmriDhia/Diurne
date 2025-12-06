<?php

namespace App\Setting\Bus\Query\GetAllPriceType;

use App\Common\Bus\Query\QueryResponse;
use App\Setting\Entity\PriceType;

class GetAllPriceTypesResponse implements QueryResponse
{
    public function __construct(private readonly array $priceTypes)
    {
    }

    public function toArray(): array
    {
        return array_map(fn(PriceType $priceType) => [
            'id' => $priceType->getId(),
            'name' => $priceType->getName(),
        ], $this->priceTypes);
    }
}
