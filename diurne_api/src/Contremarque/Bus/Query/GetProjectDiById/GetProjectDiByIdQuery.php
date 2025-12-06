<?php

namespace App\Contremarque\Bus\Query\GetProjectDiById;

use App\Common\Bus\Query\Query;

class GetProjectDiByIdQuery implements Query
{
    public function __construct(private readonly int $id)
    {
    }

    public function getId(): int
    {
        return $this->id;
    }
}
