<?php

namespace App\Contremarque\Bus\Query\GetProjectDiById;

use App\Common\Bus\Query\QueryResponse;
use App\Contremarque\Entity\ProjectDi;

class ProjectDiResponse implements QueryResponse
{
    private readonly array $projectDiData;

    public function __construct(ProjectDi $projectDi)
    {
        $this->projectDiData = $projectDi->toArray();
    }

    public function getData(): array
    {
        return $this->projectDiData;
    }
    public function toArray(): array
    {
        return $this->projectDiData;
    }
}
