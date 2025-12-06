<?php

namespace App\Invoices\Bus\Query\SupplierInvoice;

use App\Common\Bus\Query\QueryResponse;
use App\Invoices\Entity\SupplierInvoice;

final readonly class GetSupplierInvoiceByIdResponse implements QueryResponse
{
    public function __construct(private ?SupplierInvoice $invoice)
    {
    }

    public function toArray(): array
    {
        return $this->invoice?->toArray() ?? [];
    }
}
