<?php

namespace App\Setting\Bus\Query\PaymentType;

use App\Common\Bus\Query\QueryResponse;
use App\Setting\Entity\PaymentType;

class PaymentTypeQueryResponse implements QueryResponse
{
    public function __construct(private readonly array $paymentTypes)
    {
    }

    public function toArray(): array
    {
        return array_map(fn(PaymentType $paymentType) => [
            'id' => $paymentType->getId(),
            'label' => $paymentType->getLabel(),
        ], $this->paymentTypes);
    }
}