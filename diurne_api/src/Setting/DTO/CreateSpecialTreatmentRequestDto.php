<?php

namespace App\Setting\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreateSpecialTreatmentRequestDto
{
    // Add your validation constraints here
    #[Assert\NotBlank(message: 'label is required')]
    #[Assert\Type('string', message: 'label must be a string')]
    public string $label;

    #[Assert\NotBlank(message: 'price is required')]
    #[Assert\Type('float', message: 'price must be a float')]
    public float $price;

    #[Assert\NotBlank(message: 'unit is required')]
    #[Assert\Type('string', message: 'unit must be a string')]
    public string $unit;
}
