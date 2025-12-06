<?php

namespace App\Setting\Bus\Command\DominantColor;

use App\Common\Bus\Command\CommandResponse;
use App\Setting\Entity\DominantColor;

class DominantColorResponse implements CommandResponse
{
    public function __construct(private readonly DominantColor $dominantColor)
    {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->dominantColor->getId(),
            'name' => $this->dominantColor->getName(),
            'hexCode' => $this->dominantColor->getHexCode(),
        ];
    }
}
