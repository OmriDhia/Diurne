<?php

declare(strict_types=1);

namespace App\Setting\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreatePoliceRequestDto
{
    public function __construct(#[Assert\NotBlank(message: 'Label cannot be empty.')]
    #[Assert\Length(max: 50, maxMessage: 'Label cannot exceed {{ limit }} characters.')]
    public string $label)
    {
    }
}
