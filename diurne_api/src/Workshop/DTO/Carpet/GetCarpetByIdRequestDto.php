<?php

namespace App\Workshop\DTO\Carpet;

use Symfony\Component\Validator\Constraints as Assert;

class GetCarpetByIdRequestDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Positive]
        public readonly int $id
    ) {
    }
}
