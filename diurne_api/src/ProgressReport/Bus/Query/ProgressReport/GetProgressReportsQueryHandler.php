<?php

namespace App\ProgressReport\Bus\Query\ProgressReport;

use App\Common\Bus\Query\QueryHandler;
use App\ProgressReport\Repository\ProgressReportRepository;

class GetProgressReportsQueryHandler implements QueryHandler
{
    public function __construct(private ProgressReportRepository $repository)
    {
    }

    public function __invoke(GetProgressReportsQuery $query): ProgressReportQueryResponse
    {
        if (null !== $query->provisionalCalendarId) {
            $reports = $this->repository->findBy([
                'provisionalCalendar' => $query->provisionalCalendarId,
            ]);
        } else {
            $reports = $this->repository->findAll();
        }

        return new ProgressReportQueryResponse($reports);
    }
}

