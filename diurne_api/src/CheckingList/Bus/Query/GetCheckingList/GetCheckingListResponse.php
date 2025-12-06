<?php

namespace App\CheckingList\Bus\Query\GetCheckingList;

use App\Common\Bus\Query\QueryResponse;
use App\CheckingList\Entity\CheckingList;

class GetCheckingListResponse implements QueryResponse
{
    public function __construct(private array $lists)
    {
    }

    public function toArray(): array
    {
        return array_map(fn(CheckingList $c) => $c->toArray(), $this->lists);
    }
}
