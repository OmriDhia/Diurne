<?php

namespace App\Contremarque\Bus\Query\GetOrderPaymentById;

use App\Common\Bus\Query\QueryResponse;
use App\Contremarque\Entity\OrderPayment\OrderPayment;

class GetOrderPaymentByIdQueryResponse implements QueryResponse
{
    public function __construct(
        public readonly OrderPayment $orderPayment
    )
    {
    }

    public function toArray(): array
    {
        return $this->orderPayment->toArray();
    }
}