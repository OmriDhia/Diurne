<?php

namespace App\Invoices\Bus\Query\CustomerInvoiceDetail;

use App\Common\Bus\Query\QueryResponse;
use App\Invoices\Entity\CustomerInvoiceDetail;

class GetCustomerInvoiceDetailByIdResponse implements QueryResponse
{
    public function __construct(public CustomerInvoiceDetail $detail)
    {
    }

    public function toArray(): array
    {
        return $this->detail->toArray();
    }
}
