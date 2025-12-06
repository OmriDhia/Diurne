<?php

namespace App\Contremarque\Bus\Query\GetSampleById;

use App\Common\Bus\Query\Query;

class GetSampleByIdQuery implements Query
{
    public function __construct(
        private readonly int $id
    ) {}

    public function getId(): int
    {
        return $this->id;
    }
}
