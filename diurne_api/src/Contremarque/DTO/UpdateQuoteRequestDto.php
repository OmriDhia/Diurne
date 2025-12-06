<?php

namespace App\Contremarque\DTO;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class UpdateQuoteRequestDto
{
    #[Assert\NotBlank(message: 'Unit of Measurement is required.')]
    public string $unitOfMeasurement;

    #[Assert\NotBlank(message: 'Tax Rule ID is required.')]
    #[Assert\Positive(message: 'Tax Rule ID must be a positive value.')]
    public int $taxRuleId;

    #[Assert\NotBlank(message: 'Currency ID is required.')]
    #[Assert\Positive(message: 'Currency ID must be a positive value.')]
    public int $currencyId;

    #[Assert\NotBlank(message: 'Language ID is required.')]
    #[Assert\Positive(message: 'Language ID must be a positive value.')]
    public int $languageId;

    #[Assert\NotBlank(message: 'Delivery Address ID is required.')]
    #[Assert\Positive(message: 'Delivery Address ID must be a positive value.')]
    public int $deliveryAddressId;

    #[Assert\NotBlank(message: 'Transport Condition ID is required.')]
    #[Assert\Positive(message: 'Transport Condition ID must be a positive value.')]
    public int $transportConditionId;

    #[Assert\NotBlank(message: 'Invoice Address ID is required.')]
    #[Assert\Positive(message: 'Invoice Address ID must be a positive value.')]
    public int $invoiceAddressId;

    #[Assert\Callback([self::class, 'validateConversionId'])]
    public ?int $conversionId = null;

    #[Assert\Callback([self::class, 'validateShippingPrice'])]
    public ?float $shippingPrice = null;

    public ?float $withoutDiscountPrice = null;

    public ?float $additionalDiscount = null;

    public ?float $totalDiscountAmount = null;

    public ?float $totalDiscountPercentage = null;

    public ?float $totalTaxExcluded = null;


    public ?float $tax = null;

    public ?float $weight = null;

    public ?float $totalTaxIncluded = null;

    public ?bool $quoteSentToCustomer = null;

    public ?bool $transformedIntoAnOrder = null;

    public ?bool $archived = null;

    public ?string $qualificationMessage = null;

    public static function validateShippingPrice(?float $shippingPrice, ExecutionContextInterface $context): void
    {
        $object = $context->getObject();
        if (in_array($object->transportConditionId, [8, 9]) && $shippingPrice === null) {
            $context->buildViolation('Shipping price is required when transport condition is Transport quoté.')
                ->atPath('shippingPrice')
                ->addViolation();
        }
    }

    public static function validateConversionId(?int $conversionId, ExecutionContextInterface $context): void
    {
        $object = $context->getObject();
        if ($object->currencyId === 1 && $conversionId === null) {
            $context->buildViolation('Conversion ID is required when currency is Dollars.')
                ->atPath('conversionId')
                ->addViolation();
        }
    }
}
