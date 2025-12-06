<?php

namespace App\Invoices\Bus\Query\CustomerInvoiceDetail;

use App\Common\Bus\Query\Query;

class GetCustomerInvoiceDetailByIdQuery implements Query
{
    public function __construct(public int $id)
    {
    }
}
