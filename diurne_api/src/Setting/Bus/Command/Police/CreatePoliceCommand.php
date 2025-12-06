<?php

declare(strict_types=1);

namespace App\Setting\Bus\Command\Police;

class CreatePoliceCommand
{
    public function __construct(
        private readonly string $label
    ) {
    }

    public function getLabel(): string
    {
        return $this->label;
    }
}
