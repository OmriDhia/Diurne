<?php

namespace App\Contremarque\Bus\Command\CarpetComposition;

use App\Common\Bus\Command\Command;

class CreateCarpetCompositionCommand implements Command
{
    public function __construct(
        public readonly int $carpetSpecificationId,
        public readonly ?string $trame,
        public readonly int $threadCount,
        public readonly int $layerCount,
    ) {
    }

    public function getCarpetSpecificationId(): int
    {
        return $this->carpetSpecificationId;
    }

    public function getTrame(): ?string
    {
        return $this->trame;
    }

    public function getThreadCount(): int
    {
        return $this->threadCount;
    }

    public function getLayerCount(): int
    {
        return $this->layerCount;
    }
}
