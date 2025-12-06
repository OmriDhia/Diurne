<?php

declare(strict_types=1);

namespace App\Setting\Bus\Command\SpecialShape;

use App\Common\Bus\Command\Command;

class CreateSpecialShapeCommand implements Command
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
