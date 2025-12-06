<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\Location;

use App\Common\Bus\Query\Query;

class GetLocationByContremarqueQuery implements Query
{
    public function __construct(private readonly int $contremarqueId)
    {
    }

    public function getContremarqueId(): int
    {
        return $this->contremarqueId;
    }
}
