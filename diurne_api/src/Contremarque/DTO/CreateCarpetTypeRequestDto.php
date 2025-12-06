<?php

declare(strict_types=1);

namespace App\Contremarque\DTO;

use App\Contremarque\DTO\CarpetTypeAsserts\Name;
use Symfony\Component\Validator\Constraints as Assert;

class CreateCarpetTypeRequestDto
{
    public function __construct(
        #[Assert\NotBlank(message: 'name cannot be empty.')]
        #[Assert\Length(max: 50, maxMessage: 'Full name cannot exceed {{ limit }} characters.')]
        #[Name(message: 'Please enter a valid name.')]
        public string $name,
    ) {
    }
}
