<?php

namespace App\ProgressReport\Bus\Command\ProvisionalCalendar\CreateProvisionalCalendar;

use App\Common\Bus\Command\CommandHandler;
use App\ProgressReport\Bus\Command\ProvisionalCalendar\ProvisionalCalendarResponse;
use App\ProgressReport\Entity\ProvisionalCalendar;
use App\ProgressReport\Repository\ProvisionalCalendarRepository;
use App\Workshop\Repository\WorkshopOrderRepository;
use Doctrine\ORM\EntityNotFoundException;

class CreateProvisionalCalendarHandler implements CommandHandler
{
    public function __construct(
        private ProvisionalCalendarRepository $calendarRepository,
        private WorkshopOrderRepository       $workshopOrderRepository,
    )
    {
    }

    public function __invoke(CreateProvisionalCalendarCommand $command): ProvisionalCalendarResponse
    {
        $workshopOrder = $this->workshopOrderRepository->find($command->workshopOrderId);
        if (!$workshopOrder) {
            throw new EntityNotFoundException('WorkshopOrder not found');
        }

        $calendar = new ProvisionalCalendar();
        $calendar->setWorkshopOrder($workshopOrder);
        $calendar->setDeadlinPreparation($command->deadlinPreparation);
        $calendar->setDeadlinWeave($command->deadlinWeave);
        $calendar->setDeadlinFinition($command->deadlinFinition);

        $current = new \DateTimeImmutable();
        $dateEndPreparation = $current->modify('+' . $calendar->getDeadlinPreparation() . ' days');
        $dateEndWeave = $dateEndPreparation->modify('+' . $calendar->getDeadlinWeave() . ' days');
        $dateEndFinition = $dateEndWeave->modify('+' . $calendar->getDeadlinFinition() . ' days');

        $calendar->setDateEndPreparation($dateEndPreparation);
        $calendar->setDateEndWeave($dateEndWeave);
        $calendar->setDateEndFinition($dateEndFinition);
        $workshopOrder->getWorkshopInformation()->setExpectedEndDate($dateEndFinition);
        $calendar->setEventPreparation($command->eventPreparation);
        $calendar->setStopPreparation($command->stopPreparation);
        $calendar->setEventWeave($command->eventWeave);
        $calendar->setStopWeave($command->stopWeave);
        $calendar->setEventFinition($command->eventFinition);
        $calendar->setStopFinition($command->stopFinition);

        $this->calendarRepository->save($calendar, true);

        return new ProvisionalCalendarResponse($calendar);
    }
}

