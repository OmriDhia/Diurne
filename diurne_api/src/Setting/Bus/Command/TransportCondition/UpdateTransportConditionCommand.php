<?php

namespace App\Setting\Bus\Command\TransportCondition;

use App\Common\Bus\Command\Command;

class UpdateTransportConditionCommand implements Command
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
