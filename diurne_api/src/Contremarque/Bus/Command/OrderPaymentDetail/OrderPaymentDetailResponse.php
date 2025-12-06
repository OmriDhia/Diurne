<?php

namespace App\Contremarque\Bus\Command\OrderPaymentDetail;

use App\Common\Bus\Command\CommandResponse;
use App\Contremarque\Entity\OrderPayment\OrderPaymentDetail;

class OrderPaymentDetailResponse implements CommandResponse
{
    public function __construct(public OrderPaymentDetail $orderPaymentDetail)
    {
    }

    public function toArray(): array
    {
        return $this->orderPaymentDetail->toArray();
    }
}
