<?php

namespace App\Setting\Bus\Command\Currency;

use App\Common\Bus\Command\CommandResponse;
use App\Setting\Entity\Currency;

class CurrencyResponse implements CommandResponse
{
    public function __construct(private readonly Currency $currency)
    {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->currency->getId(),
            'name' => $this->currency->getName(),
        ];
    }
}
