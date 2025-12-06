<?php

namespace App\Setting\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreateCurrencyRequestDto
{
    // Add your validation constraints here
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    public string $name;
}
