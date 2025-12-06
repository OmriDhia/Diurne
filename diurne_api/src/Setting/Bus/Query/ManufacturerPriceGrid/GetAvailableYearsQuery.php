<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\ManufacturerPriceGrid;

use App\Common\Bus\Query\Query;

class GetAvailableYearsQuery implements Query
{
    public function __construct(
        private readonly ?int $manufacturerId = null
    ) {}

    public function getManufacturerId(): ?int
    {
        return $this->manufacturerId;
    }
}
