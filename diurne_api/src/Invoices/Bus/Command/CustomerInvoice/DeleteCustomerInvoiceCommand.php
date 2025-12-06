<?php

namespace App\Invoices\Bus\Command\CustomerInvoice;

use App\Common\Bus\Command\Command;

class DeleteCustomerInvoiceCommand implements Command
{
    public function __construct(public int $id) {}
}
