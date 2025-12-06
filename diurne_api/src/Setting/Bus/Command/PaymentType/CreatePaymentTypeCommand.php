<?php

namespace App\Setting\Bus\Command\PaymentType;

use App\Common\Bus\Command\Command;

class CreatePaymentTypeCommand implements Command
{
    /**
     * @param string $labe
     */
    public function __construct(public readonly string $labe)
    {
    }

    public function getLabe(): string
    {
        return $this->labe;
    }

}