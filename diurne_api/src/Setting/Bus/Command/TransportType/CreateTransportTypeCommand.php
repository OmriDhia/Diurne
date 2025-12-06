<?php

namespace App\Setting\Bus\Command\TransportType;

use App\Common\Bus\Command\Command;

class CreateTransportTypeCommand implements Command
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
