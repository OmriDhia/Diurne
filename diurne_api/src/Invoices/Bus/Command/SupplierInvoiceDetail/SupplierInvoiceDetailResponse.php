<?php

namespace App\Invoices\Bus\Command\SupplierInvoiceDetail;

use App\Common\Bus\Command\CommandResponse;
use App\Invoices\Entity\SupplierInvoiceDetail;

class SupplierInvoiceDetailResponse implements CommandResponse
{
    public function __construct(public SupplierInvoiceDetail $detail)
    {
    }

    public function toArray(): array
    {
        return $this->detail->toArray();
    }
}
