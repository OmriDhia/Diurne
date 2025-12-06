<?php

namespace App\ProgressReport\Bus\Command\ProgressReportStatus\UpdateProgressReportStatus;

use App\Common\Bus\Command\CommandHandler;
use App\ProgressReport\Bus\Command\ProgressReportStatus\ProgressReportStatusResponse;
use App\ProgressReport\Repository\ProgressReportStatusRepository;
use Doctrine\ORM\EntityNotFoundException;

class UpdateProgressReportStatusHandler implements CommandHandler
{
    public function __construct(private ProgressReportStatusRepository $statusRepository)
    {
    }

    public function __invoke(UpdateProgressReportStatusCommand $command): ProgressReportStatusResponse
    {
        $status = $this->statusRepository->find($command->id);
        if (!$status) {
            throw new EntityNotFoundException('Status not found');
        }
        if (null !== $command->status) {
            $status->setStatus($command->status);
        }
        $this->statusRepository->save($status, true);

        return new ProgressReportStatusResponse($status);
    }
}

