<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\CarpetComposition\Layer;

use App\Common\Bus\Command\Command;

class CreateLayerCommand implements Command
{
    public function __construct(
        public int $carpetCompositionId,
        public int $layerNumber,
        public ?string $remarque,
        public array $layerDetails // Array of LayerDetail DTOs
    ) {
    }
}
