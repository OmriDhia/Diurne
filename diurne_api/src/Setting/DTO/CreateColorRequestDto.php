<?php

namespace App\Setting\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreateColorRequestDto
{
    #[Assert\NotBlank(message: 'reference is required')]
    #[Assert\Type('string', message: 'reference must be a string')]
    #[Assert\Length(max: 50, maxMessage: 'reference must be 50 characters long')]
    public string $reference;

    #[Assert\Type('string', message: 'hexCode must be a string')]
    #[Assert\Length(max: 7, maxMessage: 'hexCode must be 7 characters long')]
    public ?string $hexCode = null;
}
