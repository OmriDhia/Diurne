<?php

namespace App\Invoices\Bus\Query\SupplierInvoice;

use App\Common\Bus\Query\Query;

final class GetSupplierInvoiceByIdQuery implements Query
{
    public function __construct(public int $id)
    {
    }
}
