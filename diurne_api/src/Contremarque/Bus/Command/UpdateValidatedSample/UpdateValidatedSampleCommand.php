<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\UpdateValidatedSample;

use App\Common\Bus\Command\Command;

class UpdateValidatedSampleCommand implements Command
{
    public function __construct(public int $customerInstructionId, public int $id, public ?string $rnValidatedSample, public bool $color, public ?string $libColor, public bool $velvet, public string $libVelvet, public bool $material, public ?string $libMaterial, public ?string $customerNoteOnSample)
    {
    }
}
