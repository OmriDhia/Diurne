<?php

namespace App\Setting\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateSpecialShapeRequestDto
{
    // Add your validation constraints here
    #[Assert\NotBlank(message: 'label is required')]
    #[Assert\Type('string', message: 'label must be a string')]
    public string $label;
}
