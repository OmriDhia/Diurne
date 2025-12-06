<?php

namespace App\Contremarque\Bus\Query\GetInvoiceTypes;

use App\Common\Bus\Query\QueryResponse;
use App\Contremarque\Entity\InvoiceType;

class GetInvoiceTypesResponse implements QueryResponse
{
    /** @param InvoiceType[] $items */
    public function __construct(private array $items)
    {
    }

    public function toArray(): array
    {
        return array_map(fn(InvoiceType $i) => $i->toArray(), $this->items);
    }
}
