<?php

namespace App\Invoices\Bus\Command\SupplierInvoiceDetail;

use App\Common\Bus\Command\Command;

class UpdateSupplierInvoiceDetailCommand implements Command
{
    public function __construct(
        public readonly int $id,
        public readonly ?int $supplierInvoiceId = null,
        public readonly ?int $rnId = null,
        public readonly ?string $carpetNumber = null,
        public readonly ?string $pricePerSquareMeter = null,
        public readonly ?string $invoiceSurface = null,
        public readonly ?string $invoiceAmount = null,
        public readonly ?string $theoreticalPrice = null,
        public readonly ?string $penalty = null,
        public readonly ?string $producedSurface = null,
        public readonly ?string $actualCreditAmount = null,
        public readonly ?string $theoreticalCredit = null,
        public readonly ?string $finalCarpetAmount = null,
        public readonly ?string $weight = null,
        public readonly ?string $weightPercentage = null,
        public readonly ?string $freight = null,
        public readonly ?bool $cleared = null
    ) {
    }
}
