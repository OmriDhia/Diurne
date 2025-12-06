<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetCarpetDesignOrdersByProjectDi;

use App\Common\Bus\Query\Query;

final readonly class GetCarpetDesignOrdersByProjectDiQuery implements Query
{
    public function __construct(private int $contremarqueId, private int $projectDiId)
    {
    }

    public function getContremarqueId(): int
    {
        return $this->contremarqueId;
    }

    public function getProjectDiId(): int
    {
        return $this->projectDiId;
    }
}
