<?php

namespace App\Contremarque\Bus\Query\GetTarifExpeditions;

use App\Common\Bus\Query\QueryResponse;
use App\Contremarque\Entity\TarifExpedition;

class GetTarifExpeditionsResponse implements QueryResponse
{
    /** @param TarifExpedition[] $items */
    public function __construct(private array $items)
    {
    }

    public function toArray(): array
    {
        return array_map(fn(TarifExpedition $t) => $t->toArray(), $this->items);
    }
}
