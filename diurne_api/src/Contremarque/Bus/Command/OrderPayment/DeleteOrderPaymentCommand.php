<?php

namespace App\Contremarque\Bus\Command\OrderPayment;

use App\Common\Bus\Command\Command;

class DeleteOrderPaymentCommand implements Command
{
    public function __construct(
        public readonly int $id
    )
    {
    }
}