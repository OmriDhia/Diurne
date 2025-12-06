<?php

namespace App\Contremarque\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateCarpetCompositionRequestDto
{
    #[Assert\Type('string', message: 'trame must be a string')]
    public ?string $trame = null;

    #[Assert\Type(type: 'integer', message: 'threadCount count must be an integer')]
    public ?int $threadCount = 0;

    #[Assert\Type(type: 'integer', message: 'layerCount must be an integer')]
    public ?int $layerCount = 0;
}
