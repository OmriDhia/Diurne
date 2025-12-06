<?php

namespace App\Setting\Bus\Command\TransportCondition;

use App\Common\Bus\Command\Command;

class CreateTransportConditionCommand implements Command
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
