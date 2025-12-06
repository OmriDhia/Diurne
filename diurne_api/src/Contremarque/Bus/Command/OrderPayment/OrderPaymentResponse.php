<?php

namespace App\Contremarque\Bus\Command\OrderPayment;

use App\Common\Bus\Command\CommandResponse;
use App\Contremarque\Entity\OrderPayment\OrderPayment;

class OrderPaymentResponse implements CommandResponse
{
    public function __construct(public OrderPayment $orderPayment)
    {
    }

    public function toArray(): array
    {
        return $this->orderPayment->toarray();
    }
}