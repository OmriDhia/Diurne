<?php

namespace App\Invoices\Bus\Command\SupplierInvoice;

use App\Common\Bus\Command\Command;

class DeleteSupplierInvoiceCommand implements Command
{
    public function __construct(public int $id) {}
}
