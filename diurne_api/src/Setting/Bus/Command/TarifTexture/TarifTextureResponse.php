<?php

declare(strict_types=1);

namespace App\Setting\Bus\Command\TarifTexture;

use App\Common\Bus\Command\CommandResponse;
use App\Setting\Entity\TarifTexture;

class TarifTextureResponse implements CommandResponse
{
    public function __construct(private readonly TarifTexture $tarifTexture)
    {
    }

    public function toArray(): array
    {
        return $this->tarifTexture->toArray();
    }
}

