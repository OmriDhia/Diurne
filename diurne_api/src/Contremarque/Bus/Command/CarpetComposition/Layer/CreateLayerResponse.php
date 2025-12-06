<?php

namespace App\Contremarque\Bus\Command\CarpetComposition\Layer;

use App\Common\Bus\Command\CommandResponse;
use App\Contremarque\Entity\Layer;

class CreateLayerResponse implements CommandResponse
{
    public function __construct(private readonly Layer $layer)
    {
    }

    public function toArray(): array
    {
        return $this->layer->toArray();
    }
}
