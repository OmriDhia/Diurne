<?php

namespace App\Invoices\Bus\Query\CustomerInvoice;

use App\Common\Bus\Query\Query;

final class GetCustomerInvoiceByIdQuery implements Query
{
    public function __construct(public int $id)
    {
    }
}
