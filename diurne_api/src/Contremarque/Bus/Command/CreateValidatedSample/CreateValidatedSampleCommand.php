<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\CreateValidatedSample;

use App\Common\Bus\Command\Command;

class CreateValidatedSampleCommand implements Command
{
    public function __construct(public int $customerInstructionId, public ?string $rnValidatedSample, public bool $color, public ?string $libColor, public bool $velvet, public string $libVelvet, public bool $material, public ?string $libMaterial, public ?string $customerNoteOnSample)
    {
    }
}
