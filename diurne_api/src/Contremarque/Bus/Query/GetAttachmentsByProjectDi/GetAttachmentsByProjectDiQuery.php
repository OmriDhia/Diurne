<?php

namespace App\Contremarque\Bus\Query\GetAttachmentsByProjectDi;

use App\Common\Bus\Query\Query;

class GetAttachmentsByProjectDiQuery implements Query
{
    public function __construct(
        private readonly int $projectDiId
    ) {
    }

    public function getProjectDiId(): int
    {
        return $this->projectDiId;
    }
}
