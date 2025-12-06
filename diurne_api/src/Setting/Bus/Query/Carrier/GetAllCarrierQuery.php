<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\Carrier;

use App\Common\Bus\Query\Query;

class GetAllCarrierQuery implements Query
{
    public function __construct(
        private readonly ?int $page = null,
        private readonly ?int $itemsPerPage = null,
        private readonly bool $forceRefresh = false
    ) {}

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
