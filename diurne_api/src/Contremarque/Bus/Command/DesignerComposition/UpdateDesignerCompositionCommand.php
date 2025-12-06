<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\DesignerComposition;

use App\Common\Bus\Command\Command;

class UpdateDesignerCompositionCommand implements Command
{
    public function __construct(public int $id, public ?int $materialId, public ?string $rate)
    {
    }
}
