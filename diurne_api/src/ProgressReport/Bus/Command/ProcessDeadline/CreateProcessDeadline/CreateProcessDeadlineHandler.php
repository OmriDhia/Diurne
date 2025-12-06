<?php

declare(strict_types=1);

namespace App\ProgressReport\Bus\Command\ProcessDeadline\CreateProcessDeadline;

use App\Common\Bus\Command\CommandHandler;
use App\ProgressReport\Bus\Command\ProcessDeadline\ProcessDeadlineResponse;
use App\ProgressReport\Entity\ProcessDeadline;
use App\ProgressReport\Repository\ProcessDeadlineRepository;
use App\ProgressReport\Repository\ProgressReportRepository;
use App\Setting\Repository\ProgressReportProcessRepository;
use Doctrine\ORM\EntityNotFoundException;

class CreateProcessDeadlineHandler implements CommandHandler
{
    public function __construct(
        private ProcessDeadlineRepository $deadlineRepository,
        private ProgressReportRepository $progressReportRepository,
        private ProgressReportProcessRepository $processRepository,
    ) {
    }

    public function __invoke(CreateProcessDeadlineCommand $command): ProcessDeadlineResponse
    {
        $progressReport = $this->progressReportRepository->find($command->progressReportId);
        if (!$progressReport) {
            throw new EntityNotFoundException('ProgressReport not found');
        }
        $process = $this->processRepository->find($command->processId);
        if (!$process) {
            throw new EntityNotFoundException('ProgressReportProcess not found');
        }

        $deadline = new ProcessDeadline();
        $deadline->setProgressReport($progressReport);
        $deadline->setProcess($process);
        $deadline->setDateStart($command->dateStart);
        $deadline->setDateEnd($command->dateEnd);
        $deadline->setComment($command->comment);

        $this->deadlineRepository->save($deadline, true);

        return new ProcessDeadlineResponse($deadline);
    }
}
