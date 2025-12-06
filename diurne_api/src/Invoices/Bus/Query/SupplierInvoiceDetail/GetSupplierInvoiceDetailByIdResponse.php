<?php

namespace App\Invoices\Bus\Query\SupplierInvoiceDetail;

use App\Common\Bus\Query\QueryResponse;
use App\Invoices\Entity\SupplierInvoiceDetail;

class GetSupplierInvoiceDetailByIdResponse implements QueryResponse
{
    public function __construct(public SupplierInvoiceDetail $detail)
    {
    }

    public function toArray(): array
    {
        return $this->detail->toArray();
    }
}
