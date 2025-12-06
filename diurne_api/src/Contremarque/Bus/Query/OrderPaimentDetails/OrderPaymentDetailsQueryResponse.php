<?php

namespace App\Contremarque\Bus\Query\OrderPaimentDetails;

use App\Common\Bus\Query\QueryResponse;

class OrderPaymentDetailsQueryResponse implements QueryResponse
{
    public function __construct(
        public readonly array $orderPayments
    )
    {
    }

    public function toArray(): array
    {
        return ['orderPaymentDetails' => $this->orderPayments];
    }
}