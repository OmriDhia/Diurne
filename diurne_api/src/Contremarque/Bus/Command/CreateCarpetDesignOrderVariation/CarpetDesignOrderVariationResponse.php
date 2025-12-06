<?php

namespace App\Contremarque\Bus\Command\CreateCarpetDesignOrderVariation;

use App\Common\Bus\Command\CommandResponse;
use App\Contremarque\Entity\CarpetDesignOrder;

class CarpetDesignOrderVariationResponse implements CommandResponse
{
    public function __construct(private readonly CarpetDesignOrder $order)
    {
    }


    public function toArray(): array
    {
        return [
            'id' => $this->order->getId(),
            'di' => $this->order->getProjectDi()->getDemandeNumber(),
            'variation' => $this->order->getVariation(),
        ];
    }
}
