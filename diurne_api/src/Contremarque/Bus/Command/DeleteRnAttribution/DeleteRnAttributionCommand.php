<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\DeleteRnAttribution;

use App\Common\Bus\Command\Command;

class DeleteRnAttributionCommand implements Command
{
    public function __construct(
        public readonly int $id
    )
    {
    }
}