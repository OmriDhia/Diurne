<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\MaterialPrice;

use App\Common\Bus\Query\Query;

class GetAllMaterialPriceQuery implements Query
{
    public function __construct(
        private readonly ?int $page,
        private readonly ?int $itemsPerPage,
        private readonly bool $forceRefresh = false
    )
    {
    }

    public function getPage(): ?int
    {
        return $this->page;
    }

    public function getItemsPerPage(): ?int
    {
        return $this->itemsPerPage;
    }

    public function isForceRefresh(): bool
    {
        return $this->forceRefresh;
    }
}
