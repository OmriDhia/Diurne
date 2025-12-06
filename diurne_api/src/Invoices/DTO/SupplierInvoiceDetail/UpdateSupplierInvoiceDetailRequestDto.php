<?php

namespace App\Invoices\DTO\SupplierInvoiceDetail;

use App\Common\DTO\BaseDto;
use App\Invoices\Bus\Command\SupplierInvoiceDetail\UpdateSupplierInvoiceDetailCommand;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateSupplierInvoiceDetailRequestDto extends BaseDto
{
    #[Assert\Type('int')]
    public ?int $supplierInvoiceId = null;

    #[Assert\Type('int')]
    public ?int $rnId = null;

    #[Assert\Type('string')]
    public ?string $carpetNumber = null;

    #[Assert\Type('numeric')]
    public ?string $pricePerSquareMeter = null;

    #[Assert\Type('numeric')]
    public ?string $invoiceSurface = null;

    #[Assert\Type('numeric')]
    public ?string $invoiceAmount = null;

    #[Assert\Type('numeric')]
    public ?string $theoreticalPrice = null;

    #[Assert\Type('numeric')]
    public ?string $penalty = null;

    #[Assert\Type('numeric')]
    public ?string $producedSurface = null;

    #[Assert\Type('numeric')]
    public ?string $actualCreditAmount = null;

    #[Assert\Type('numeric')]
    public ?string $theoreticalCredit = null;

    #[Assert\Type('numeric')]
    public ?string $finalCarpetAmount = null;

    #[Assert\Type('numeric')]
    public ?string $weight = null;

    #[Assert\Type('numeric')]
    public ?string $weightPercentage = null;

    #[Assert\Type('numeric')]
    public ?string $freight = null;

    #[Assert\Type('bool')]
    public ?bool $cleared = null;

    public function toUpdateSupplierInvoiceDetailCommand(int $id): UpdateSupplierInvoiceDetailCommand
    {
        return new UpdateSupplierInvoiceDetailCommand(
            $id,
            $this->supplierInvoiceId,
            $this->rnId,
            $this->carpetNumber,
            $this->pricePerSquareMeter,
            $this->invoiceSurface,
            $this->invoiceAmount,
            $this->theoreticalPrice,
            $this->penalty,
            $this->producedSurface,
            $this->actualCreditAmount,
            $this->theoreticalCredit,
            $this->finalCarpetAmount,
            $this->weight,
            $this->weightPercentage,
            $this->freight,
            $this->cleared
        );
    }
}
