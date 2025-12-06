<?php

namespace App\Setting\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateModelRequestDto
{
    public function __construct(
        #[Assert\Length(max: 50, maxMessage: 'Code cannot exceed {{ limit }} characters.')]
        public ?string $code,
        #[Assert\Type(type: 'int', message: 'Number max must be integer type.')]
        public ?int $number_max
    ) {}
}
