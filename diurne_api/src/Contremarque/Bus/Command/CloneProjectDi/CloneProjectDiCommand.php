<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\CloneProjectDi;

use App\Common\Bus\Command\Command;

class CloneProjectDiCommand implements Command
{
    public function __construct(private readonly int $projectDiId)
    {
    }

    public function getProjectDiId(): int
    {
        return $this->projectDiId;
    }
}
