<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Query\ProgressReport\GetProgressReportList;

use App\Common\Bus\Query\QueryHandler;
use App\MobileAppApi\Repository\ProgressReportRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class GetProgressReportListHandler implements QueryHandler
{
    public function __construct(
        private readonly ProgressReportRepository $repository
    ) {
    }

    public function __invoke(GetProgressReportListQuery $query): array
    {
        return $this->repository->findAll();
    }
}
