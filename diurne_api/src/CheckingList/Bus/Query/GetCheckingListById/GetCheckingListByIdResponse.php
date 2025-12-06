<?php

namespace App\CheckingList\Bus\Query\GetCheckingListById;

use App\Common\Bus\Query\QueryResponse;
use App\CheckingList\Entity\CheckingList;

class GetCheckingListByIdResponse implements QueryResponse
{
    public function __construct(private CheckingList $checkingList)
    {
    }

    public function toArray(): array
    {
        return $this->checkingList->toArray();
    }
}
