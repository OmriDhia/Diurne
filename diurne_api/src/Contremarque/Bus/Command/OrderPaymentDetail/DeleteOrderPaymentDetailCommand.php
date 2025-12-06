<?php

namespace App\Contremarque\Bus\Command\OrderPaymentDetail;

use App\Common\Bus\Command\Command;

class DeleteOrderPaymentDetailCommand implements Command
{
    public function __construct(
        public readonly int $orderPaymentDetailId
    )
    {
    }

    public function getOrderPaymentDetailId(): int
    {
        return $this->orderPaymentDetailId;
    }
}