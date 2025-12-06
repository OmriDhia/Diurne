<?php

namespace App\Invoices\Bus\Command\SupplierInvoiceDetail;

use App\Common\Bus\Command\Command;

class CreateSupplierInvoiceDetailCommand implements Command
{
    public function __construct(
        public readonly int     $supplierInvoiceId,
        public readonly int     $rnId,
        public readonly ?string $carpetNumber,
        public readonly string  $pricePerSquareMeter,
        public readonly string  $invoiceSurface,
        public readonly string  $invoiceAmount,
        public readonly string  $theoreticalPrice,
        public readonly string  $penalty,
        public readonly string  $producedSurface,
        public readonly string  $actualCreditAmount,
        public readonly string  $theoreticalCredit,
        public readonly string  $finalCarpetAmount,
        public readonly string  $weight,
        public readonly string  $weightPercentage,
        public readonly string  $freight,
        public readonly bool    $cleared = false
    )
    {
    }
}
