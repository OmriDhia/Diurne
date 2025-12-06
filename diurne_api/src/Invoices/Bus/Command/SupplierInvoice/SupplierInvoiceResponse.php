<?php

namespace App\Invoices\Bus\Command\SupplierInvoice;

use App\Common\Bus\Command\CommandResponse;
use App\Invoices\Entity\SupplierInvoice;

class SupplierInvoiceResponse implements CommandResponse
{
    public function __construct(private readonly SupplierInvoice $invoice)
    {
    }

    public function toArray(): array
    {
        return $this->invoice->toArray();
    }
}
