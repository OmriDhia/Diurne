<?php

namespace App\Invoices\DTO;

use DateTimeInterface;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateSupplierInvoiceRequestDto
{
    public function __construct(
        #[Assert\NotNull]
        public int                $id,
        #[Assert\NotBlank]
        public string             $invoiceNumber,
        #[Assert\NotNull]
        public DateTimeInterface  $invoiceDate,
        #[Assert\NotNull]
        public int                $manufacturerId,
        #[Assert\NotBlank]
        public string             $packingList,
        #[Assert\NotBlank]
        public string             $airWay,
        #[Assert\NotBlank]
        public string             $fretTotal,
        #[Assert\NotNull]
        public int                $currencyId,
        #[Assert\NotNull]
        public int                $authorId,
        #[Assert\NotBlank]
        public string             $amountOther,
        #[Assert\NotBlank]
        public string             $weight,
        public ?string            $description = null,
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
