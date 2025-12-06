<?php

namespace App\Setting\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateDominantColorRequestDto
{
    public function __construct(#[Assert\NotBlank(message: 'Name is required')]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Name cannot exceed 255 characters'
    )]
    public ?string $name = null, #[Assert\Length(
        max: 7,
        maxMessage: 'Hex code must be 7 characters long'
    )]
    public ?string $hexCode = null)
    {
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
