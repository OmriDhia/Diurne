<?php

namespace App\ProgressReport\Bus\Command\ProgressReport\CreateProgressReport;

use App\Common\Bus\Command\CommandHandler;
use App\ProgressReport\Bus\Command\ProgressReport\ProgressReportResponse;
use App\ProgressReport\Entity\ProgressReport;
use App\ProgressReport\Repository\ProgressReportRepository;
use App\ProgressReport\Repository\ProgressReportStatusRepository;
use App\ProgressReport\Repository\ProvisionalCalendarRepository;
use App\User\Repository\UserRepository;
use Doctrine\ORM\EntityNotFoundException;

class CreateProgressReportHandler implements CommandHandler
{
    public function __construct(
        private ProgressReportRepository $progressReportRepository,
        private UserRepository $userRepository,
        private ProgressReportStatusRepository $statusRepository,
        private ProvisionalCalendarRepository $calendarRepository,
    ) {
    }

    public function __invoke(CreateProgressReportCommand $command): ProgressReportResponse
    {
        $author = $this->userRepository->findById($command->authorId);
        if (!$author) {
            throw new EntityNotFoundException('User not found');
        }
        $status = $this->statusRepository->find($command->statusId);
        if (!$status) {
            throw new EntityNotFoundException('Status not found');
        }

        $calendar = null;
        if ($command->provisionalCalendarId) {
            $calendar = $this->calendarRepository->find($command->provisionalCalendarId);
            if (!$calendar) {
                throw new EntityNotFoundException('ProvisionalCalendar not found');
            }
        }

        $progressReport = new ProgressReport();
        $progressReport->setAuthor($author);
        $progressReport->setDatePR($command->datePr);
        $progressReport->setComment($command->comment);
        $progressReport->setDateEvent($command->dateEvent);
        $progressReport->setDateWorkshop($command->dateWorkshop);
        $progressReport->setTissage($command->tissage);
        $progressReport->setStatus($status);
        if ($calendar) {
            $progressReport->setProvisionalCalendar($calendar);
        }

        $this->progressReportRepository->save($progressReport, true);

        return new ProgressReportResponse($progressReport);
    }
}

