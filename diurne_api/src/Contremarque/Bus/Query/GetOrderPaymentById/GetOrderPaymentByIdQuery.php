<?php

namespace App\Contremarque\Bus\Query\GetOrderPaymentById;

use App\Common\Bus\Query\Query;

class GetOrderPaymentByIdQuery implements Query
{
    public function __construct(private readonly int $orderId)
    {
    }
    public function getOrderId(): int
    {
        return $this->orderId;
    }
}