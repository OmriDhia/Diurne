<?php

namespace App\CheckingList\Bus\Query\GetQualityCheckById;

use App\Common\Bus\Query\Query;

class GetQualityCheckByIdQuery implements Query
{
    public function __construct(public int $id)
    {
    }
}
