<?php

namespace App\Contremarque\Bus\Query\GetProjectDiById;

use Exception;
use App\Common\Bus\Query\QueryHandler;
use App\Contremarque\Repository\ProjectDiRepository;

class GetProjectDiByIdQueryHandler implements QueryHandler
{
    public function __construct(private readonly ProjectDiRepository $projectDiRepository)
    {
    }

    public function __invoke(GetProjectDiByIdQuery $query): ProjectDiResponse
    {
        $projectDi = $this->projectDiRepository->find($query->getId());

        if (!$projectDi) {
            throw new Exception('ProjectDi not found');
        }

        return new ProjectDiResponse($projectDi);
    }
}
