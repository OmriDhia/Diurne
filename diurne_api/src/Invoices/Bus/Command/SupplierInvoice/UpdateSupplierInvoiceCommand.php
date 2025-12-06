<?php

namespace App\Invoices\Bus\Command\SupplierInvoice;

use App\Common\Bus\Command\Command;
use DateTimeInterface;

class UpdateSupplierInvoiceCommand implements Command
{
    public function __construct(
        public int                $id,
        public string             $invoiceNumber,
        public DateTimeInterface  $invoiceDate,
        public ?int               $manufacturerId = null,
        public string             $packingList,
        public string             $airWay,
        public string             $fretTotal,
        public int                $currencyId,
        public int                $authorId,
        public string             $amountOther,
        public string             $weight,
        public string             $description,
        public ?bool              $isincluded = null,
        public ?string            $weightTotal = null,
        public ?string            $surfaceTotal = null,
        public ?string            $invoiceTotal = null,
        public ?string            $theoreticalTotal = null,
        public ?string            $amountTheoretical = null,
        public ?string            $amountReal = null,
        public ?int               $creditNumber = null,
        public ?DateTimeInterface $creditDate = null,
        public string             $paymentReal = '0',
        public ?string            $paymentTheoretical = null,
        public ?DateTimeInterface $paymentDate = null
    )
    {
    }
}
