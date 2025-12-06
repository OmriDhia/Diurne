<?php

namespace App\Invoices\DTO\CustomerInvoiceDetail;

use App\Common\DTO\BaseDto;
use App\Invoices\Bus\Command\CustomerInvoiceDetail\UpdateCustomerInvoiceDetailCommand;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateCustomerInvoiceDetailRequestDto extends BaseDto
{
    #[Assert\Type('int')]
    public ?int $customerInvoiceId = null;

    #[Assert\Type('int')]
    public ?int $carpetOrderDetailId = null;

    #[Assert\Type('bool')]
    public ?bool $cleared = null;

    #[Assert\Type('string')]
    public ?string $rn = null;

    #[Assert\Type('int')]
    public ?int $collectionId = null;

    #[Assert\Type('int')]
    public ?int $modelId = null;

    #[Assert\Type('numeric')]
    public ?string $m2 = null;

    #[Assert\Type('numeric')]
    public ?string $sqft = null;

    #[Assert\Type('numeric')]
    public ?string $ht = null;

    #[Assert\Type('numeric')]
    public ?string $ttc = null;

    #[Assert\Type('string')]
    public ?string $refCommand = null;

    #[Assert\Type('string')]
    public ?string $refQuote = null;

    public function toUpdateCustomerInvoiceDetailCommand(int $id): UpdateCustomerInvoiceDetailCommand
    {
        return new UpdateCustomerInvoiceDetailCommand(
            $id,
            $this->customerInvoiceId,
            $this->carpetOrderDetailId,
            $this->cleared,
            $this->refCommand,
            $this->refQuote,
            $this->rn,
            $this->collectionId,
            $this->modelId,
            $this->m2,
            $this->sqft,
            $this->ht,
            $this->ttc
        );
    }
}
