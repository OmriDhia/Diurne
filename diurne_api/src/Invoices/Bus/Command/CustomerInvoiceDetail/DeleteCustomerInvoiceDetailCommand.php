<?php

namespace App\Invoices\Bus\Command\CustomerInvoiceDetail;

use App\Common\Bus\Command\Command;

class DeleteCustomerInvoiceDetailCommand implements Command
{
    public function __construct(public readonly int $id)
    {
    }
}
