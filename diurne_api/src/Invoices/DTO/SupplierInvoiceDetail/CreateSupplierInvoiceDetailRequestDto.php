<?php

namespace App\Invoices\DTO\SupplierInvoiceDetail;

use App\Common\DTO\BaseDto;
use Symfony\Component\Validator\Constraints as Assert;

class CreateSupplierInvoiceDetailRequestDto extends BaseDto
{
    #[Assert\NotBlank]
    #[Assert\Type('int')]
    public ?int $supplierInvoiceId = null;

    #[Assert\NotBlank]
    #[Assert\Type('int')]
    public ?int $rnId = null;

    // carpetNumber is optional now (nullable)
    public ?string $carpetNumber = null;

    #[Assert\NotBlank]
    #[Assert\Type('numeric')]
    public ?string $pricePerSquareMeter = null;

    #[Assert\NotBlank]
    #[Assert\Type('numeric')]
    public ?string $invoiceSurface = null;

    #[Assert\NotBlank]
    #[Assert\Type('numeric')]
    public ?string $invoiceAmount = null;

    #[Assert\NotBlank]
    #[Assert\Type('numeric')]
    public ?string $theoreticalPrice = null;

    #[Assert\NotBlank]
    #[Assert\Type('numeric')]
    public ?string $penalty = null;

    #[Assert\NotBlank]
    #[Assert\Type('numeric')]
    public ?string $producedSurface = null;

    #[Assert\NotBlank]
    #[Assert\Type('numeric')]
    public ?string $actualCreditAmount = null;

    #[Assert\NotBlank]
    #[Assert\Type('numeric')]
    public ?string $theoreticalCredit = null;

    #[Assert\NotBlank]
    #[Assert\Type('numeric')]
    public ?string $finalCarpetAmount = null;

    #[Assert\NotBlank]
    #[Assert\Type('numeric')]
    public ?string $weight = null;

    #[Assert\NotBlank]
    #[Assert\Type('numeric')]
    public ?string $weightPercentage = null;

    #[Assert\NotBlank]
    #[Assert\Type('numeric')]
    public ?string $freight = null;

    #[Assert\Type('bool')]
    public bool $cleared = false;
}
