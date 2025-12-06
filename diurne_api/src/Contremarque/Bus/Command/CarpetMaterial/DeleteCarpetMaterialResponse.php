<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\CarpetMaterial;

use App\Common\Bus\Command\CommandResponse;

class DeleteCarpetMaterialResponse implements CommandResponse
{
    public function __construct(private readonly int $id)
    {
    }

    public function toArray(): array
    {
        return ['deletedId' => $this->id];
    }
}

