<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\Currency;

use App\Common\Bus\Query\QueryResponse;
use App\Setting\Entity\Currency;

final readonly class GetByIdCurrencyResponse implements QueryResponse
{
    public function __construct(private ?Currency $currency)
    {
    }

    public function toArray(): array
    {
        return $this->currency ? [
            'id' => $this->currency->getId(),
            'name' => $this->currency->getName(),
        ] : [];
    }
}
