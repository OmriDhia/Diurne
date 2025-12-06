<?php

namespace App\Setting\Bus\Command\PaymentType;

use App\Common\Bus\Command\CommandResponse;
use App\Setting\Entity\PaymentType;

class PaymentTypeResponse implements CommandResponse
{
    public function __construct(private readonly PaymentType $paymentType)
    {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->paymentType->getId(),
            'label' => $this->paymentType->getLabel(),
        ];
    }
}