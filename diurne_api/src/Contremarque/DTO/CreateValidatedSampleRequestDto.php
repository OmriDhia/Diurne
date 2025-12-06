<?php

declare(strict_types=1);

namespace App\Contremarque\DTO;

class CreateValidatedSampleRequestDto
{
    // Add a constructor to initialize the properties
    public function __construct(public ?string $rnValidatedSample, public bool $color, public ?string $libColor, public bool $velvet, public string $libVelvet, public bool $material, public ?string $libMaterial, public ?string $customerNoteOnSample)
    {
    }
}
