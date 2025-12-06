<?php

namespace App\Contremarque\Bus\Query\GetOrderPaymentDetailById;

use App\Common\Bus\Query\Query;

class GetOrderPaymentDetailByIdQuery implements Query
{
    public function __construct(private readonly int $orderId)
    {
    }
    public function getOrderDetailId(): int
    {
        return $this->orderId;
    }
}