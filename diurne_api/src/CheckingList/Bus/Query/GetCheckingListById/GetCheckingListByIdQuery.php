<?php

namespace App\CheckingList\Bus\Query\GetCheckingListById;

use App\Common\Bus\Query\Query;

class GetCheckingListByIdQuery implements Query
{
    public function __construct(public int $id)
    {
    }
}
