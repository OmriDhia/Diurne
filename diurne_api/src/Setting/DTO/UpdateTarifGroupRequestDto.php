<?php

namespace App\Setting\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateTarifGroupRequestDto
{
    // Add your validation constraints here
    #[Assert\NotBlank(message: 'year is required')]
    #[Assert\Type('string', message: 'year must be a string')]
    public string $year;
}
