<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\ManufacturerPriceGrid;

use App\Common\Bus\Query\Query;

class GetManufacturerPriceHistoryQuery implements Query
{
    public function __construct(
        private readonly int $manufacturerId,
        private readonly int $qualityId,
        private readonly ?int $limit = 10
    ) {}

    public function getManufacturerId(): int
    {
        return $this->manufacturerId;
    }

    public function getQualityId(): int
    {
        return $this->qualityId;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }
}
