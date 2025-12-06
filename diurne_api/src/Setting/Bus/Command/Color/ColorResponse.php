<?php

namespace App\Setting\Bus\Command\Color;

use App\Common\Bus\Command\CommandResponse;
use App\Setting\Entity\Color;

class ColorResponse implements CommandResponse
{
    public function __construct(private readonly Color $color)
    {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->color->getId(),
            'reference' => $this->color->getReference(),
            'hexCode' => $this->color->getHexCode(),
        ];
    }
}
