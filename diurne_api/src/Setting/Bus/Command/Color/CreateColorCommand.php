<?php

namespace App\Setting\Bus\Command\Color;

use App\Common\Bus\Command\Command;

class CreateColorCommand implements Command
{
    public function __construct(
        public readonly string $reference,
        public readonly ?string $hexCode = null
    ) {
    }

    public function getReference(): string
    {
        return $this->reference;
    }

    public function getHexCode(): ?string
    {
        return $this->hexCode;
    }
}
