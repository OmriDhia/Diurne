<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Query\ProgressReport\GetProgressReport;

use App\Common\Bus\Query\QueryHandler;
use App\MobileAppApi\Entity\ProgressReport;
use App\MobileAppApi\Repository\ProgressReportRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[AsMessageHandler]
final class GetProgressReportHandler implements QueryHandler
{
    public function __construct(
        private readonly ProgressReportRepository $repository
    ) {
    }

    public function __invoke(GetProgressReportQuery $query): ProgressReport
    {
        $progressReport = $this->repository->find($query->id);

        if (!$progressReport) {
            throw new NotFoundHttpException('ProgressReport not found');
        }

        return $progressReport;
    }
}
