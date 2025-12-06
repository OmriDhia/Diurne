<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\DeleteDesignerComposition;

use App\Common\Bus\Command\Command;

class DeleteDesignerCompositionCommand implements Command
{
    public function __construct(
        private readonly int $id
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }
}
