<?php

namespace App\Contremarque\DTO\OrderPayment;

use App\Common\DTO\BaseDto;
use Symfony\Component\Validator\Constraints as Assert;

class CreateOrderPaymentDetailRequestDto extends BaseDto
{
    #[Assert\NotBlank]
    #[Assert\Type("int")]
    public ?int $orderPaymentId = null;

    #[Assert\Type("int")]
    public ?int $quoteId = null;

    #[Assert\Type("int")]
    public ?int $quoteDetailId = null;

    #[Assert\Type("int")]
    public ?int $customerInvoiceId = null;

    #[Assert\Type("int")]
    public ?int $customerInvoiceDetailId = null;

    public ?string $commandNumber = null;

    #[Assert\Type("int")]
    public ?int $orderInvoiceId = null;

    #[Assert\NotBlank]
    #[Assert\Length(max: 10)]
    public string $rn;

    #[Assert\Type("numeric")]
    public string $distribution = "0";

    #[Assert\Type("numeric")]
    public string $allocatedAmountTtc = "0";

    #[Assert\Type("numeric")]
    public string $remainingAmountTtc = "0";

    #[Assert\Type("numeric")]
    public string $totalAmountTtc = "0";

    #[Assert\Type("numeric")]
    public string $tva = "0";

    #[Assert\Type("numeric")]
    public string $allocatedAmountHt = "0";

    #[Assert\Type("bool")]
    public bool $cleared = false;
}