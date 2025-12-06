<?php

namespace App\Setting\Bus\Command\Currency;

use App\Common\Bus\Command\Command;

class CreateCurrencyCommand implements Command
{
    public function __construct(
        public readonly string $name,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }
}
