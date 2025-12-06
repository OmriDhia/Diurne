<?php

namespace App\Contremarque\DTO\OrderPayment;

use App\Common\DTO\BaseDto;
use App\Contremarque\Bus\Command\OrderPaymentDetail\UpdateOrderPaymentDetailCommand;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateOrderPaymentDetailRequestDto extends BaseDto
{
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

    #[Assert\Length(max: 10)]
    public ?string $rn = "";

    #[Assert\Type("numeric")]
    public ?string $distribution = "0";

    #[Assert\Type("numeric")]
    public ?string $allocatedAmountTtc = "0";

    #[Assert\Type("numeric")]
    public ?string $remainingAmountTtc = "0";

    #[Assert\Type("numeric")]
    public ?string $totalAmountTtc = "0";

    #[Assert\Type("numeric")]
    public ?string $tva = "0";

    #[Assert\Type("numeric")]
    public ?string $allocatedAmountHt = "0";

    #[Assert\Type("bool")]
    public ?bool $cleared = false;

    public function toUpdateOrderPaymentDetailCommand(int $id): UpdateOrderPaymentDetailCommand
    {
        return new UpdateOrderPaymentDetailCommand(
            $id,
            $this->orderPaymentId,
            $this->quoteId,
            $this->quoteDetailId,
            $this->customerInvoiceId,
            $this->customerInvoiceDetailId,
            $this->commandNumber,
            $this->orderInvoiceId,
            $this->rn,
            $this->distribution,
            $this->allocatedAmountTtc,
            $this->remainingAmountTtc,
            $this->totalAmountTtc,
            $this->tva,
            $this->allocatedAmountHt,
            $this->cleared
        );
    }}