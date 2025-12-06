<?php

namespace App\Contremarque\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreateCarpetCompositionRequestDto
{
    #[Assert\Type('string', message: 'trame must be a string')]
    public ?string $trame = null;

    #[Assert\NotBlank(message: 'threadCount count is required')]
    #[Assert\Type(type: 'integer', message: 'threadCount count must be an integer')]
    public int $threadCount;

    #[Assert\NotBlank(message: 'layerCount is required')]
    #[Assert\Type(type: 'integer', message: 'layerCount must be an integer')]
    public int $layerCount;
}
