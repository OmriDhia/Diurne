<?php

namespace App\Setting\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreatePaymentTypeRequestDto
{
    #[Assert\Type('string', message: 'Label must be a string')]
    #[Assert\Length(max: 255, maxMessage: 'Label cannot exceed 255 characters')]
    #[Assert\NotBlank]
    public string $label;
}