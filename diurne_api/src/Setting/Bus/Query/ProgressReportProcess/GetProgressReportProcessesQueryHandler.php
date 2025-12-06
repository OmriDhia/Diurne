<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\ProgressReportProcess;

use App\Common\Bus\Query\QueryHandler;
use App\Setting\Repository\ProgressReportProcessRepository;

class GetProgressReportProcessesQueryHandler implements QueryHandler
{
    public function __construct(private readonly ProgressReportProcessRepository $repository)
    {
    }

    public function __invoke(GetProgressReportProcessesQuery $query): ProgressReportProcessQueryResponse
    {
        $processes = $this->repository->findAll();
        return new ProgressReportProcessQueryResponse($processes);
    }
}
