<?php

namespace App\Setting\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreateTransportTypeRequestDto
{
    // Add your validation constraints here
    #[Assert\NotBlank(message: 'Name is required')]
    #[Assert\Type('string', message: 'Name must be a string')]
    public string $name;
}
