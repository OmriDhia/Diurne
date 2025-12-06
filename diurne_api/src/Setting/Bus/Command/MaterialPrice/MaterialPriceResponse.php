<?php

namespace App\Setting\Bus\Command\MaterialPrice;

use App\Common\Bus\Command\CommandResponse;
use App\Setting\Entity\MaterialPrice;

class MaterialPriceResponse implements CommandResponse
{
    public function __construct(private readonly MaterialPrice $materialPrice)
    {
    }

    public function toArray(): array
    {
        return $this->materialPrice->toArray();
    }
}
