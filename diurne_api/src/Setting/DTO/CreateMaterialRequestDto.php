<?php

declare(strict_types=1);

namespace App\Setting\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreateMaterialRequestDto
{
    public function __construct(#[Assert\NotBlank]
    #[Assert\Type('string')]
    public string $reference, #[Assert\All([
        new Assert\Collection([
            'language_id' => new Assert\Required(new Assert\Type('integer')),
            'label' => new Assert\Required(new Assert\Type('string')),
        ]),
    ])]
    public array $descriptions)
    {
    }
}
