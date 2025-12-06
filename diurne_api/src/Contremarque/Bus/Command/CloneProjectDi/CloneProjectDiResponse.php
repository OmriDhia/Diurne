<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\CloneProjectDi;

use App\Common\Bus\Command\CommandResponse;
use App\Contremarque\Entity\ProjectDi;

class CloneProjectDiResponse implements CommandResponse
{
    public function __construct(private readonly ProjectDi $projectDi)
    {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->projectDi->getId(),
            'demande_number' => $this->projectDi->getDemandeNumber(),
        ];
    }
}
