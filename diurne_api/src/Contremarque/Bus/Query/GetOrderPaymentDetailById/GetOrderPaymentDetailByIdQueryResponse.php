<?php

namespace App\Contremarque\Bus\Query\GetOrderPaymentDetailById;

use App\Common\Bus\Query\QueryResponse;
use App\Contremarque\Entity\OrderPayment\OrderPaymentDetail;

class GetOrderPaymentDetailByIdQueryResponse implements QueryResponse
{
    public function __construct(
        public readonly OrderPaymentDetail $orderPayment
    )
    {
    }

    public function toArray(): array
    {
        return $this->orderPayment->toArray();
    }
}