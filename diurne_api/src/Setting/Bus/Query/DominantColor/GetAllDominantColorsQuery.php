<?php

namespace App\Setting\Bus\Query\DominantColor;

use App\Common\Bus\Query\Query;

class GetAllDominantColorsQuery implements Query
{
    public function __construct(
        private readonly int $page,
        private readonly int $itemsPerPage
    ) {
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getItemsPerPage(): int
    {
        return $this->itemsPerPage;
    }
}
