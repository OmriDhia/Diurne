<?php

declare(strict_types=1);

namespace App\Contremarque\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class DeleteDesignerCompositionRequestDto
{
    public function __construct(#[Assert\NotBlank]
    #[Assert\Type('integer')]
    public int $id)
    {
    }
}
