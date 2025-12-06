<?php

namespace App\CheckingList\Bus\Query\GetQualityRespectById;

use App\Common\Bus\Query\Query;

class GetQualityRespectByIdQuery implements Query
{
    public function __construct(public int $id)
    {
    }
}
