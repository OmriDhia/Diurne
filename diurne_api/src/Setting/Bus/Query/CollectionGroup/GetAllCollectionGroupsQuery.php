<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\CollectionGroup;

use App\Common\Bus\Query\Query;

class GetAllCollectionGroupsQuery implements Query
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
