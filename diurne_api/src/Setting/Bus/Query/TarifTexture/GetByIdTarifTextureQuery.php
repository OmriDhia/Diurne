<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\TarifTexture;

use App\Common\Bus\Query\Query;

class GetByIdTarifTextureQuery implements Query
{
    public function __construct(private readonly int $id)
    {
    }

    public function getId(): int
    {
        return $this->id;
    }
}

