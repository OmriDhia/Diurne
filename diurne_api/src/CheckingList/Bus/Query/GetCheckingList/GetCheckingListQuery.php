<?php

namespace App\CheckingList\Bus\Query\GetCheckingList;

use App\Common\Bus\Query\Query;

class GetCheckingListQuery implements Query
{
    public function __construct(public readonly ?int $workshopOrderId = null)
    {
    }
}
