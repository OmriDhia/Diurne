<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\CarpetComposition\Layer;

use App\Common\Bus\Command\Command;

class DeleteLayerCommand implements Command
{
    public function __construct(
        private readonly int $carpetCompositionId,
        private readonly array $layerIds
    ) {}

    public function getCarpetCompositionId(): int
    {
        return $this->carpetCompositionId;
    }

    public function getLayerIds(): array
    {
        return $this->layerIds;
    }
}
