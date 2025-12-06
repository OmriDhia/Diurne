<?php

declare(strict_types=1);

namespace App\Event\Bus\Command\Event;

use DomainException;
use DateTimeImmutable;
use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Contact\Entity\Customer;
use App\Contact\Repository\CustomerRepository;
use App\Contremarque\Entity\Contremarque;
use App\Contremarque\Repository\ContremarqueRepository;
use App\Event\Entity\Event;
use App\Event\Entity\EventNomenclature;
use App\Event\Repository\EventNomenclatureRepository;
use App\Event\Repository\EventRepository;

class CreateEventCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly EventNomenclatureRepository $eventNomenclatureRepository,
        private readonly EventRepository $eventRepository,
        private readonly CustomerRepository $customerRepository,
        private readonly ContremarqueRepository $contremarqueRepository
    ) {
    }

    public function __invoke(CreateEventCommand $command): EventResponse
    {
        $eventNomenclature = $this->eventNomenclatureRepository->find((int) $command->getNomenclatureId());

        if (!$eventNomenclature instanceof EventNomenclature) {
            throw new ResourceNotFoundException();
        }
        $event = new Event();
        $event->setNomenclature($eventNomenclature);
        if (!empty($command->getCustomerId())) {
            $customer = $this->customerRepository->find((int) $command->getCustomerId());
            if (!$customer instanceof Customer) {
                throw new DomainException(sprintf('Customer not found %s', $command->getCustomerId()));
            }
            $event->setCustomer($customer);
        }
        $event->setCommentaire($command->getCommentaire());
        $eventData = $command->getEventDate();
        if (!empty($eventData)) {
            $eventData = date('Y-m-d H:i:s', strtotime($eventData));
            $eventData = new DateTimeImmutable($eventData);
            $event->setEventDate($eventData);
        }
        $next_reminder_deadline = $command->getNextReminderDeadline();

        if (!empty($next_reminder_deadline)) {
            $next_reminder_deadline = date('Y-m-d H:i:s', strtotime($next_reminder_deadline));
            $next_reminder_deadline = new DateTimeImmutable($next_reminder_deadline);
            $event->setNextReminderDeadline($next_reminder_deadline);
        }
        if (null !== $command->getDisabledReminder()) {
            $event->setReminderDisabled((bool) $command->getDisabledReminder());
        }
        if (null !== $command->getPeoplePresent()) {
            $event->setPeoplePresent((array) $command->getPeoplePresent());
        }

        if (null !== $command->getContremarqueId()) {
            $contremarque = $this->contremarqueRepository->find((int) $command->getContremarqueId());
            if ($contremarque instanceof Contremarque) {
                $event->setContremarque($contremarque);
            }
        }
        /*
         * @todo add set Contremarque and set quote
         */

        $this->eventRepository->persist($event);
        $this->eventRepository->flush();

        return new EventResponse($event);
    }
}
