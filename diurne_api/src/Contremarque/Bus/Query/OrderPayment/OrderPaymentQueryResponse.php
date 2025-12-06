<?php

namespace App\Contremarque\Bus\Query\OrderPayment;

use App\Common\Bus\Query\QueryResponse;

class OrderPaymentQueryResponse implements QueryResponse
{
    public function __construct(
        public readonly array $orderPayments,
        public readonly int   $total,
        public readonly int   $pages
    )
    {
    }

    public function toArray(): array
    {
        return [
            'data' => $this->orderPayments,
            'meta' => [
                'total' => $this->total,
                'pages' => $this->pages
            ]
        ];
    }
}