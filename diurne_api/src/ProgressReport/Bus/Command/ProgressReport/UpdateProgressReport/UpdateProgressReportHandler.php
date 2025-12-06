<?php

namespace App\ProgressReport\Bus\Command\ProgressReport\UpdateProgressReport;

use App\Common\Bus\Command\CommandHandler;
use App\ProgressReport\Bus\Command\ProgressReport\ProgressReportResponse;
use App\ProgressReport\Repository\ProgressReportRepository;
use App\ProgressReport\Repository\ProgressReportStatusRepository;
use App\ProgressReport\Repository\ProvisionalCalendarRepository;
use App\User\Repository\UserRepository;
use Doctrine\ORM\EntityNotFoundException;

class UpdateProgressReportHandler implements CommandHandler
{
    public function __construct(
        private ProgressReportRepository $progressReportRepository,
        private UserRepository $userRepository,
        private ProgressReportStatusRepository $statusRepository,
        private ProvisionalCalendarRepository $calendarRepository,
    ) {
    }

    public function __invoke(UpdateProgressReportCommand $command): ProgressReportResponse
    {
        $progressReport = $this->progressReportRepository->find($command->id);
        if (!$progressReport) {
            throw new EntityNotFoundException('ProgressReport not found');
        }

        if ($command->authorId) {
            $author = $this->userRepository->findById($command->authorId);
            if (!$author) {
                throw new EntityNotFoundException('User not found');
            }
            $progressReport->setAuthor($author);
        }
        if ($command->datePr) {
            $progressReport->setDatePR($command->datePr);
        }
        if ($command->comment !== null) {
            $progressReport->setComment($command->comment);
        }
        if ($command->dateEvent) {
            $progressReport->setDateEvent($command->dateEvent);
        }
        if ($command->dateWorkshop) {
            $progressReport->setDateWorkshop($command->dateWorkshop);
        }
        if ($command->tissage !== null) {
            $progressReport->setTissage($command->tissage);
        }
        if ($command->statusId) {
            $status = $this->statusRepository->find($command->statusId);
            if (!$status) {
                throw new EntityNotFoundException('Status not found');
            }
            $progressReport->setStatus($status);
        }
        if ($command->provisionalCalendarId !== null) {
            if ($command->provisionalCalendarId === 0) {
                $progressReport->setProvisionalCalendar(null);
            } else {
                $calendar = $this->calendarRepository->find($command->provisionalCalendarId);
                if (!$calendar) {
                    throw new EntityNotFoundException('ProvisionalCalendar not found');
                }
                $progressReport->setProvisionalCalendar($calendar);
            }
        }

        $this->progressReportRepository->save($progressReport, true);

        return new ProgressReportResponse($progressReport);
    }
}

