<?php

declare(strict_types=1);

namespace App\Contact\Bus\Query\GetPrescripters;

use App\Common\Bus\Query\Query;

final class GetPrescriptersQuery implements Query
{
    public function __construct(
        public ?int $page = null,
        public ?int $itemsPerPage = null,
    ) {
    }

    public function getPage(): int|null
    {
        return $this->page;
    }

    public function getItemsPerPage(): int|null
    {
        return $this->itemsPerPage;
    }
}
