<?php

namespace App\Contremarque\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreateThreadRequestDto
{
    #[Assert\NotBlank(message: 'threadNumber is required')]
    #[Assert\Type('int', message: 'threadNumber must be an integer')]
    public int $threadNumber;

    #[Assert\NotBlank(message: 'techColorId is required')]
    #[Assert\Type('int', message: 'techColorId must be an integer')]
    public int $techColorId;
}
