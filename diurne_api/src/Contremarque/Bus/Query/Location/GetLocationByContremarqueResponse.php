<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\Location;

use App\Common\Bus\Query\QueryResponse;

class GetLocationByContremarqueResponse implements QueryResponse
{
    public function __construct(public $locations)
    {
    }

    public function toArray(): array
    {
        return $this->locations;
    }
}
