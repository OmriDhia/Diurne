<?php

declare(strict_types=1);

namespace App\Setting\Bus\Command\TarifTexture;

use App\Common\Bus\Command\Command;

class DeleteTarifTextureCommand implements Command
{
    public function __construct(public readonly int $id)
    {
    }
}

