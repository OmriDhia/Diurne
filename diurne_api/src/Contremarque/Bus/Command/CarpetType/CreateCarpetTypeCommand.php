<?php

namespace App\Contremarque\Bus\Command\CarpetType;

use App\Common\Bus\Command\Command;

class CreateCarpetTypeCommand implements Command
{
    public function __construct(
        private readonly string $name
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }
}
