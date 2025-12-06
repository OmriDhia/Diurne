<?php

namespace App\Setting\Bus\Command\SpecialShape;

use App\Common\Bus\Command\Command;

class UpdateSpecialShapeCommand implements Command
{
    public function __construct(
        public readonly int $id,
        public readonly string $label
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

}
