<?php

namespace App\ProgressReport\Bus\Command\ProgressReport\DeleteProgressReport;

use App\Common\Bus\Command\CommandHandler;
use App\ProgressReport\Repository\ProgressReportRepository;
use Doctrine\ORM\EntityNotFoundException;

class DeleteProgressReportHandler implements CommandHandler
{
    public function __construct(private ProgressReportRepository $progressReportRepository)
    {
    }

    public function __invoke(DeleteProgressReportCommand $command): void
    {
        $progressReport = $this->progressReportRepository->find($command->id);
        if (!$progressReport) {
            throw new EntityNotFoundException('ProgressReport not found');
        }
        $this->progressReportRepository->remove($progressReport);
        $this->progressReportRepository->flush();
    }
}

