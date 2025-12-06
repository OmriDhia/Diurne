<?php

namespace App\Setting\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreateModelRequestDto
{
    public function __construct(#[Assert\NotBlank(message: 'Code cannot be empty.')]
    #[Assert\Length(max: 50, maxMessage: 'Code cannot exceed {{ limit }} characters.')]
    public string $code, #[Assert\NotBlank(message: 'Number max cannot be empty.')]
    public int $number_max)
    {
    }
}
