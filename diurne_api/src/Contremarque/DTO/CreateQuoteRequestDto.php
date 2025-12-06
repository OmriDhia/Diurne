<?php

declare(strict_types=1);

namespace App\Contremarque\DTO;

use Symfony\Component\Validator\Constraints as Assert;
use App\Contremarque\Validator\ValidateQuoteFields;

#[ValidateQuoteFields]
class CreateQuoteRequestDto
{
    public function __construct(
        #[Assert\NotBlank(message: "Currency ID is required and cannot be blank.")]
        #[Assert\Positive(message: "Currency ID must be a positive integer.")]
        public int     $currencyId,

        #[Assert\NotBlank(message: "Language ID is required and cannot be blank.")]
        #[Assert\Positive(message: "Language ID must be a positive integer.")]
        public int     $languageId,

        #[Assert\NotBlank(message: "Tax Rule ID is required and cannot be blank.")]
        #[Assert\Positive(message: "Tax Rule ID must be a positive integer.")]
        public int     $taxRuleId,

        #[Assert\NotBlank(message: "Unit of Measurement is required and cannot be blank.")]
        public string  $unitOfMeasurement,

        #[Assert\NotBlank(message: "Delivery Address ID is required and cannot be blank.")]
        #[Assert\Positive(message: "Delivery Address ID must be a positive integer.")]
        public int     $deliveryAddressId,

        #[Assert\NotBlank(message: "Invoice Address ID is required and cannot be blank.")]
        #[Assert\Positive(message: "Invoice Address ID must be a positive integer.")]
        public int     $invoiceAddressId,

        public ?int    $conversionId = null,

        #[Assert\NotBlank(message: "Transport Condition ID is required and cannot be blank.")]
        #[Assert\Positive(message: "Transport Condition ID must be a positive integer.")]
        public int     $transportConditionId,

        public ?bool   $isValidated = false,
        public ?string $qualificationMessage = null,


        public ?float  $shippingPrice = null,

        public ?float  $additionalDiscount = null,
        public ?float  $weight = null,
        public ?bool   $quoteSentToCustomer = null,
        public ?bool   $transformedIntoAnOrder = false,
        public ?bool   $archived = false,
        public ?float  $withoutDiscountPrice = null,
        public ?float  $totalDiscountAmount = null,
        public ?float  $totalDiscountPercentage = null,
        public ?float  $totalTaxExcluded = null,
        public ?float  $tax = null,
        public ?float  $totalTaxIncluded = null,
    )
    {
    }


}