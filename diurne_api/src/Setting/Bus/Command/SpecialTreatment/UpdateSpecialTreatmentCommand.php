<?php

namespace App\Setting\Bus\Command\SpecialTreatment;

use App\Common\Bus\Command\Command;

class UpdateSpecialTreatmentCommand implements Command
{
    public function __construct(
        public readonly int $id,
        public readonly string $label,
        public readonly float $price,
        public readonly string $unit,
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

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getUnit(): string
    {
        return $this->unit;
    }
}
