<?php

namespace App\CheckingList\Bus\Query\GetLayersValidationById;

use App\Common\Bus\Query\Query;

class GetLayersValidationByIdQuery implements Query
{
    public function __construct(public readonly int $id)
    {
    }
}
