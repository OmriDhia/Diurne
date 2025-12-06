<?php

namespace App\Invoices\Bus\Command\SupplierInvoiceDetail;

use App\Common\Bus\Command\Command;

class DeleteSupplierInvoiceDetailCommand implements Command
{
    public function __construct(public readonly int $id)
    {
    }
}
