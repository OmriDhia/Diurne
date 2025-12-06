<?php

namespace App\Setting\Bus\Command\PaymentType;

use App\Common\Bus\Command\Command;

class DeletePaymentTypeCommand implements Command
{
    public function __construct(
        public readonly int $id
    )
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

}