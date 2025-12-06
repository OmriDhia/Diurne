<?php

namespace App\ProgressReport\Bus\Command\ProvisionalCalendar\UpdateProvisionalCalendar;

use App\Common\Bus\Command\CommandHandler;
use App\ProgressReport\Bus\Command\ProvisionalCalendar\ProvisionalCalendarResponse;
use App\ProgressReport\Repository\ProvisionalCalendarRepository;
use App\Workshop\Repository\WorkshopOrderRepository;
use Doctrine\ORM\EntityNotFoundException;

class UpdateProvisionalCalendarHandler implements CommandHandler
{
    public function __construct(
        private ProvisionalCalendarRepository $calendarRepository,
        private WorkshopOrderRepository       $workshopOrderRepository,
    )
    {
    }

    public function __invoke(UpdateProvisionalCalendarCommand $command): ProvisionalCalendarResponse
    {
        $calendar = $this->calendarRepository->find($command->id);
        if (!$calendar) {
            throw new EntityNotFoundException('ProvisionalCalendar not found');
        }
        if ($command->workshopOrderId) {
            $workshopOrder = $this->workshopOrderRepository->find($command->workshopOrderId);
            if (!$workshopOrder) {
                throw new EntityNotFoundException('WorkshopOrder not found');
            }
            $calendar->setWorkshopOrder($workshopOrder);
        }
        if (null !== $command->deadlinPreparation) {
            $calendar->setDeadlinPreparation($command->deadlinPreparation);
        }
        if (null !== $command->deadlinWeave) {
            $calendar->setDeadlinWeave($command->deadlinWeave);
        }
        if (null !== $command->deadlinFinition) {
            $calendar->setDeadlinFinition($command->deadlinFinition);
        }

        $current = new \DateTimeImmutable();
        $dateEndPreparation = $current->modify('+' . $calendar->getDeadlinPreparation() . ' days');
        $dateEndWeave = $dateEndPreparation->modify('+' . $calendar->getDeadlinWeave() . ' days');
        $dateEndFinition = $dateEndWeave->modify('+' . $calendar->getDeadlinFinition() . ' days');

        $calendar->setDateEndPreparation($dateEndPreparation);
        $calendar->setDateEndWeave($dateEndWeave);
        $calendar->setDateEndFinition($dateEndFinition);
        $calendar->getWorkshopOrder()->getWorkshopInformation()->setExpectedEndDate($dateEndFinition);
        if (null !== $command->eventPreparation) {
            $calendar->setEventPreparation($command->eventPreparation);
        }
        if (null !== $command->stopPreparation) {
            $calendar->setStopPreparation($command->stopPreparation);
        }
        if (null !== $command->eventWeave) {
            $calendar->setEventWeave($command->eventWeave);
        }
        if (null !== $command->stopWeave) {
            $calendar->setStopWeave($command->stopWeave);
        }
        if (null !== $command->eventFinition) {
            $calendar->setEventFinition($command->eventFinition);
        }
        if (null !== $command->stopFinition) {
            $calendar->setStopFinition($command->stopFinition);
        }

        $this->calendarRepository->save($calendar, true);

        return new ProvisionalCalendarResponse($calendar);
    }
}

