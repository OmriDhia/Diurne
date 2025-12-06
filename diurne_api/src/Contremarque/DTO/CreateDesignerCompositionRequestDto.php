<?php

namespace App\Contremarque\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreateDesignerCompositionRequestDto
{
    public function __construct(#[Assert\NotBlank]
    #[Assert\Type('integer')]
    public int $materialId, #[Assert\NotBlank]
    #[Assert\Type('integer')]
    public int $carpetSpecificationId, #[Assert\NotBlank]
    #[Assert\Type('numeric')]
    public string $rate)
    {
    }
}
