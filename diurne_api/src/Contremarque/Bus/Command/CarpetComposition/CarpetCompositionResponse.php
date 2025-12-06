<?php

namespace App\Contremarque\Bus\Command\CarpetComposition;

use App\Common\Bus\Command\CommandResponse;
use App\Contremarque\Entity\CarpetComposition;

class CarpetCompositionResponse implements CommandResponse
{
    public function __construct(private readonly CarpetComposition $carpetComposition)
    {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->carpetComposition->getId(),
            'trame' => $this->carpetComposition->getTrame(),
            'threadCount' => $this->carpetComposition->getThreadCount(),
            'layerCount' => $this->carpetComposition->getLayerCount(),
        ];
    }
}
