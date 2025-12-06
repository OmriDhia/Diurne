<?php

namespace App\ProgressReport\Bus\Query\ProgressReportStatus;

use App\Common\Bus\Query\QueryHandler;
use App\ProgressReport\Repository\ProgressReportStatusRepository;

class GetProgressReportStatusesQueryHandler implements QueryHandler
{
    public function __construct(private ProgressReportStatusRepository $repository)
    {
    }

    public function __invoke(GetProgressReportStatusesQuery $query): ProgressReportStatusQueryResponse
    {
        $statuses = $this->repository->findAll();
        return new ProgressReportStatusQueryResponse($statuses);
    }
}

