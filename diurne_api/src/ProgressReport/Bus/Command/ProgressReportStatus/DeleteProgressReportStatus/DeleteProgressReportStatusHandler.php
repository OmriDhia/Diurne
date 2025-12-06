<?php

namespace App\ProgressReport\Bus\Command\ProgressReportStatus\DeleteProgressReportStatus;

use App\Common\Bus\Command\CommandHandler;
use App\ProgressReport\Repository\ProgressReportStatusRepository;
use Doctrine\ORM\EntityNotFoundException;

class DeleteProgressReportStatusHandler implements CommandHandler
{
    public function __construct(private ProgressReportStatusRepository $statusRepository)
    {
    }

    public function __invoke(DeleteProgressReportStatusCommand $command): void
    {
        $status = $this->statusRepository->find($command->id);
        if (!$status) {
            throw new EntityNotFoundException('Status not found');
        }
        $this->statusRepository->remove($status);
        $this->statusRepository->flush();
    }
}

