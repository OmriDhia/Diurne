<?php

namespace App\Invoices\DTO\CustomerInvoiceDetail;

use App\Common\DTO\BaseDto;
use Symfony\Component\Validator\Constraints as Assert;

class CreateCustomerInvoiceDetailRequestDto extends BaseDto
{
    #[Assert\NotBlank]
    #[Assert\Type('int')]
    public ?int $customerInvoiceId = null;

    #[Assert\NotBlank]
    #[Assert\Type('int')]
    public ?int $carpetOrderDetailId = null;

    #[Assert\Type('bool')]
    public bool $cleared = false;

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
}
