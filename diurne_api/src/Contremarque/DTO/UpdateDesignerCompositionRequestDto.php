<?php

declare(strict_types=1);

namespace App\Contremarque\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateDesignerCompositionRequestDto
{
    public function __construct(#[Assert\NotNull]
    public ?int $materialId, #[Assert\NotBlank]
    public ?string $rate)
    {
    }
}
