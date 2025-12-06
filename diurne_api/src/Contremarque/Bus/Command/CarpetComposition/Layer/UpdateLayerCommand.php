<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\CarpetComposition\Layer;

use App\Common\Bus\Command\Command;

class UpdateLayerCommand implements Command
{
    public function __construct(
        public int $carpetCompositionId,
        public int $layerId,
        public ?int $layerNumber,
        public ?string $remarque,
        public ?array $layerDetails // Array of LayerDetail DTOs
    ) {
    }
}
