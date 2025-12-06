<?php

namespace App\Contremarque\Bus\Command\CarpetComposition\Layer;

use App\Common\Bus\Command\CommandResponse;
use App\Contremarque\Entity\Layer;

class UpdateLayerResponse implements CommandResponse
{
    public function __construct(private readonly Layer $layer)
    {
    }

    public function toArray(): array
    {
        return $this->layer->toArray();
    }
}
