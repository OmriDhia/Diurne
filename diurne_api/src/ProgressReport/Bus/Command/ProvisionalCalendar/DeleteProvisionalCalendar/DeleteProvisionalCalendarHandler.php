<?php

namespace App\ProgressReport\Bus\Command\ProvisionalCalendar\DeleteProvisionalCalendar;

use App\Common\Bus\Command\CommandHandler;
use App\ProgressReport\Repository\ProvisionalCalendarRepository;
use Doctrine\ORM\EntityNotFoundException;

class DeleteProvisionalCalendarHandler implements CommandHandler
{
    public function __construct(private ProvisionalCalendarRepository $calendarRepository)
    {
    }

    public function __invoke(DeleteProvisionalCalendarCommand $command): void
    {
        $calendar = $this->calendarRepository->find($command->id);
        if (!$calendar) {
            throw new EntityNotFoundException('ProvisionalCalendar not found');
        }
        $this->calendarRepository->remove($calendar);
        $this->calendarRepository->flush();
    }
}

