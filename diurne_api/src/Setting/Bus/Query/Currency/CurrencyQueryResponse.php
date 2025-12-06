<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\Currency;

use App\Common\Bus\Query\QueryResponse;

class CurrencyQueryResponse implements QueryResponse
{
    public function __construct(private readonly array $all_currency)
    {
    }

    public function toArray(): array
    {
        $response = [];

        foreach ($this->all_currency as $currency) {
            $response[] = [
                'id' => $currency->getId(),
                'name' => $currency->getName(),
            ];
        }

        return $response;
    }
}
