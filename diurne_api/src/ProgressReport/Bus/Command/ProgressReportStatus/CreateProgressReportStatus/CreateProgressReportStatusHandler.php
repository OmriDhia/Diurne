<?php

namespace App\ProgressReport\Bus\Command\ProgressReportStatus\CreateProgressReportStatus;

use App\Common\Bus\Command\CommandHandler;
use App\ProgressReport\Bus\Command\ProgressReportStatus\ProgressReportStatusResponse;
use App\ProgressReport\Entity\ProgressReportStatus;
use App\ProgressReport\Repository\ProgressReportStatusRepository;

class CreateProgressReportStatusHandler implements CommandHandler
{
    public function __construct(private ProgressReportStatusRepository $statusRepository)
    {
    }

    public function __invoke(CreateProgressReportStatusCommand $command): ProgressReportStatusResponse
    {
        $status = new ProgressReportStatus();
        $status->setStatus($command->status);
        $this->statusRepository->save($status, true);

        return new ProgressReportStatusResponse($status);
    }
}

