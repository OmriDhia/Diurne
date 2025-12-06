<?php

namespace App\Invoices\Bus\Query\CustomerInvoiceDetail;

use App\Common\Bus\Query\QueryResponse;

class GetAllCustomerInvoiceDetailsResponse implements QueryResponse
{
    public function __construct(public array $details)
    {
    }

    public function toArray(): array
    {
        return ['customerInvoiceDetails' => $this->details];
    }
}
