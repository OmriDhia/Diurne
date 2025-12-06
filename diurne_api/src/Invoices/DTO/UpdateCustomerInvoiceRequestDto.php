<?php

namespace App\Invoices\DTO;

use DateTimeInterface;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateCustomerInvoiceRequestDto
{
    public function __construct(
        #[Assert\NotBlank]
        public string            $invoiceNumber,
        #[Assert\NotNull]
        public DateTimeInterface $invoiceDate,
        #[Assert\NotNull]
        public int               $invoiceType,
        #[Assert\NotNull]
        public int               $carrierId,
        #[Assert\NotNull]
        public int               $customerId,
        public ?int              $carpetOrderId = null,
        public ?int              $quantityTotal = null,
        public string            $shippingCostsHt = '0',
        public string            $billed = '0',
        public ?string           $payment = null,
        public ?string           $totalHt = null,
        public ?string           $amountHt = null,
        public ?string           $amountTva = null,
        public ?string           $amountTtc = null,
        public ?int              $prescriberId = null,
        public ?int              $invoiceTypeEntityId = null,
        public ?int              $currencyId = null,
        public ?int              $conversionId = null,
        public ?int              $languageId = null,
        public ?int              $mesurementId = null,
        public ?int              $regulationId = null,
        public ?int              $tarifExpeditionId = null,
        public ?int              $rnId = null,
        public ?int              $taxRuleId = null,
        public ?string           $description = null,
        public ?string           $number = null,
        public ?string           $project = null
    )
    {
    }
}
