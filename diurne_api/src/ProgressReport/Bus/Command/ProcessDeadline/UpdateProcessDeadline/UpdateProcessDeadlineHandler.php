<?php

declare(strict_types=1);

namespace App\ProgressReport\Bus\Command\ProcessDeadline\UpdateProcessDeadline;

use App\Common\Bus\Command\CommandHandler;
use App\ProgressReport\Bus\Command\ProcessDeadline\ProcessDeadlineResponse;
use App\ProgressReport\Repository\ProcessDeadlineRepository;
use App\Setting\Repository\ProgressReportProcessRepository;
use Doctrine\ORM\EntityNotFoundException;

class UpdateProcessDeadlineHandler implements CommandHandler
{
    public function __construct(
        private ProcessDeadlineRepository $deadlineRepository,
        private ProgressReportProcessRepository $processRepository,
    ) {
    }

    public function __invoke(UpdateProcessDeadlineCommand $command): ProcessDeadlineResponse
    {
        $deadline = $this->deadlineRepository->find($command->id);
        if (!$deadline) {
            throw new EntityNotFoundException('ProcessDeadline not found');
        }

        if (null !== $command->processId) {
            $process = $this->processRepository->find($command->processId);
            if (!$process) {
                throw new EntityNotFoundException('ProgressReportProcess not found');
            }
            $deadline->setProcess($process);
        }
        if (null !== $command->dateStart) {
            $deadline->setDateStart($command->dateStart);
        }
        if (null !== $command->dateEnd) {
            $deadline->setDateEnd($command->dateEnd);
        }
        if (null !== $command->comment) {
            $deadline->setComment($command->comment);
        }

        $this->deadlineRepository->save($deadline, true);

        return new ProcessDeadlineResponse($deadline);
    }
}
