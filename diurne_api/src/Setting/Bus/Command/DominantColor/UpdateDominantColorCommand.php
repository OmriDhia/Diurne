<?php

namespace App\Setting\Bus\Command\DominantColor;

use App\Common\Bus\Command\Command;

class UpdateDominantColorCommand implements Command
{
    public function __construct(
        public readonly int $id,
        public readonly ?string $name,
        public readonly ?string $hexCode
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getHexCode(): ?string
    {
        return $this->hexCode;
    }
}
