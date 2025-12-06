<?php

namespace App\Invoices\Bus\Query\SupplierInvoiceDetail;

use App\Common\Bus\Query\Query;

class GetSupplierInvoiceDetailByIdQuery implements Query
{
    public function __construct(public int $id)
    {
    }
}
