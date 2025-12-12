<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Command\ProgressReport\DeleteProgressReport;

use App\Common\Bus\Command\CommandHandler;
use App\MobileAppApi\Repository\ProgressReportRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[AsMessageHandler]
final class DeleteProgressReportHandler implements CommandHandler
{
    public function __construct(
        private readonly ProgressReportRepository $repository
    ) {
    }

    public function __invoke(DeleteProgressReportCommand $command): void
    {
        $progressReport = $this->repository->find($command->id);

        if (!$progressReport) {
            throw new NotFoundHttpException('ProgressReport not found');
        }

        $this->repository->remove($progressReport, true);
    }
}
