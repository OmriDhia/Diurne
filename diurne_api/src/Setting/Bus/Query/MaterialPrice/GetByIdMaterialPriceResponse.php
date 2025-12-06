<?php

namespace App\Setting\Bus\Query\MaterialPrice;

use App\Common\Bus\Query\QueryResponse;
use App\Setting\Entity\MaterialPrice;

final readonly class GetByIdMaterialPriceResponse implements QueryResponse
{
    public function __construct(private array $materialPrices)
    {
    }

    public function toArray(): array
    {
        /* @var MaterialPrice $materialPrice */
        return array_map(fn($materialPrice) => [
            'id' => $materialPrice->getId(),
            'publicPrice' => $materialPrice->getPublicPrice(),
            'bigProjectPrice' => $materialPrice->getBigProjectPrice(),
        ], $this->materialPrices);
    }
}
