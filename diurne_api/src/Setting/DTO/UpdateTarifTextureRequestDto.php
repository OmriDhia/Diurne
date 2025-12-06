<?php

namespace App\Setting\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateTarifTextureRequestDto
{
    #[Assert\NotBlank(message: 'year is required')]
    #[Assert\Type('string', message: 'year must be a string')]
    #[Assert\Length(max: 20, maxMessage: 'year must be 20 characters long')]
    public string $year;
}

