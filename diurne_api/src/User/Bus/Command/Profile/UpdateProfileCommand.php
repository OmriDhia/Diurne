<?php

declare(strict_types=1);

namespace App\User\Bus\Command\Profile;

use App\Common\Bus\Command\Command;

class UpdateProfileCommand implements Command
{
    private string $name;

    private float $discount;

    public function __construct(private readonly string $profileId)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getProfileId(): string
    {
        return $this->profileId;
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
