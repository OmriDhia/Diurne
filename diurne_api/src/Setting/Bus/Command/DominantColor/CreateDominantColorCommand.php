<?php

namespace App\Setting\Bus\Command\DominantColor;

use App\Common\Bus\Command\Command;

class CreateDominantColorCommand implements Command
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $hexCode = null
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getHexCode(): ?string
    {
        return $this->hexCode;
    }
}
