<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\TarifTexture;

use App\Common\Bus\Query\QueryResponse;
use App\Setting\Entity\TarifTexture;

class GetByIdTarifTextureResponse implements QueryResponse
{
    public function __construct(private readonly TarifTexture $tarifTexture)
    {
    }

    public function toArray(): array
    {
        return $this->tarifTexture->toArray();
    }
}

