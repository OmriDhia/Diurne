<?php

namespace App\Contremarque\Bus\Query\GetContremarqueByPrescriberId;

use App\Common\Bus\Query\QueryResponse;
use App\Contremarque\Entity\Contremarque;

class ContremarqueResponse implements QueryResponse
{
    public function __construct(
        public array $contremarques
    ) {
    }

    public function toArray(): array
    {
        return array_map(fn(Contremarque $contremarque) => $contremarque->toArray(), $this->contremarques);
    }
}
