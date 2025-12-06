<?php

namespace App\Invoices\Bus\Command\CustomerInvoice;

use App\Common\Bus\Command\CommandResponse;
use App\Invoices\Entity\CustomerInvoice;

class CustomerInvoiceResponse implements CommandResponse
{
    public function __construct(private readonly CustomerInvoice $invoice)
    {
    }

    public function toArray(): array
    {
        return $this->invoice->toArray();
    }
}
