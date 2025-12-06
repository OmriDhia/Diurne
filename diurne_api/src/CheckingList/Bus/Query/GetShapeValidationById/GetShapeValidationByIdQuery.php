<?php

namespace App\CheckingList\Bus\Query\GetShapeValidationById;

use App\Common\Bus\Query\Query;

class GetShapeValidationByIdQuery implements Query
{
    public function __construct(public readonly int $id)
    {
    }
}
