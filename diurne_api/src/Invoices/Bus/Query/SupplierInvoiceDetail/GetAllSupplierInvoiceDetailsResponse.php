<?php

namespace App\Invoices\Bus\Query\SupplierInvoiceDetail;

use App\Common\Bus\Query\QueryResponse;

class GetAllSupplierInvoiceDetailsResponse implements QueryResponse
{
    public function __construct(public array $details)
    {
    }

    public function toArray(): array
    {
        return ['supplierInvoiceDetails' => $this->details];
    }
}
