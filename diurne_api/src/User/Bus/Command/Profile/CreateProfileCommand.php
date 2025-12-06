<?php

declare(strict_types=1);

namespace App\User\Bus\Command\Profile;

use App\Common\Bus\Command\Command;

class CreateProfileCommand implements Command
{
    private float $discount;

    public function __construct(private readonly string $name)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setDiscount(float $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function getDiscount(): float
    {
        return $this->discount;
    }
}
