<?php

namespace App\Setting\Bus\Command\TransportType;

use App\Common\Bus\Command\Command;

class UpdateTransportTypeCommand implements Command
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
