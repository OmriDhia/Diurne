<?php

namespace App\Invoices\Bus\Command\CustomerInvoiceDetail;

use App\Common\Bus\Command\CommandResponse;
use App\Invoices\Entity\CustomerInvoiceDetail;

class CustomerInvoiceDetailResponse implements CommandResponse
{
    public function __construct(public CustomerInvoiceDetail $detail)
    {
    }

    public function toArray(): array
    {
        return $this->detail->toArray();
    }
}
