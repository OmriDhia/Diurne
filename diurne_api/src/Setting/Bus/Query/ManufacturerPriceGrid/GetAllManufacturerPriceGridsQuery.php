<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\ManufacturerPriceGrid;

use App\Common\Bus\Query\Query;

class GetAllManufacturerPriceGridsQuery implements Query
{
    public function __construct(
        private readonly ?int $manufacturerId = null,
        private readonly ?int $tarifGroupId = null,
        private readonly ?int $qualityId = null,
        private readonly ?int $page = null,
        private readonly ?int $itemsPerPage = null,
        private readonly bool $onlyActive = true
    ) {}

    public function getManufacturerId(): ?int
    {
        return $this->manufacturerId;
    }

    public function getTarifGroupId(): ?int
    {
        return $this->tarifGroupId;
    }

    public function getQualityId(): ?int
    {
        return $this->qualityId;
    }

    public function getPage(): ?int
    {
        return $this->page;
    }

    public function getItemsPerPage(): ?int
    {
        return $this->itemsPerPage;
    }

    public function isOnlyActive(): bool
    {
        return $this->onlyActive;
    }
}
