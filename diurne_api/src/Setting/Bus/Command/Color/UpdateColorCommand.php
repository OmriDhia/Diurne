<?php

namespace App\Setting\Bus\Command\Color;

use App\Common\Bus\Command\Command;

class UpdateColorCommand implements Command
{
    public function __construct(
        public readonly int $id,
        public readonly string $reference,
        public readonly ?string $hexCode
    ) {
    }

    public function getId(): int
    {
        return $this->id;
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
