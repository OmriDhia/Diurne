<?php

namespace App\Setting\Bus\Command\PaymentType;

use App\Common\Bus\Command\Command;

class UpdatePaymentTypeCommand implements Command
{
    public function __construct(
        public readonly int     $id,
        public readonly ?string $label = null)
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getLabel(): string|null
    {
        return $this->label;
    }
}
