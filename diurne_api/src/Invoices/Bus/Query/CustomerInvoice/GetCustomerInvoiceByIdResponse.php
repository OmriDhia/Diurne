<?php

namespace App\Invoices\Bus\Query\CustomerInvoice;

use App\Common\Bus\Query\QueryResponse;
use App\Invoices\Entity\CustomerInvoice;

final readonly class GetCustomerInvoiceByIdResponse implements QueryResponse
{
    public function __construct(private ?CustomerInvoice $invoice)
    {
    }

    public function toArray(): array
    {
        return $this->invoice?->toArray() ?? [];
    }
}
