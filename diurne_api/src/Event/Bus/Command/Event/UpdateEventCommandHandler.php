<?php

declare(strict_types=1);

namespace App\Event\Bus\Command\Event;

use DomainException;
use DateTimeImmutable;
use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Contact\Entity\Customer;
use App\Contact\Repository\CustomerRepository;
use App\Contremarque\Repository\ContremarqueRepository;
use App\Event\Entity\Event;
use App\Event\Repository\EventNomenclatureRepository;
use App\Event\Repository\EventRepository;

class UpdateEventCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly EventRepository $eventRepository,
        private readonly EventNomenclatureRepository $eventNomenclatureRepository,
        private readonly CustomerRepository $customerRepository,
        private readonly ContremarqueRepository $contremarqueRepository
    ) {
    }

    public function __invoke(UpdateEventCommand $command): EventResponse
    {
        $event = $this->eventRepository->find($command->getEventId());

        if (!$event instanceof Event) {
            throw new ResourceNotFoundException('Event not found');
        }

        if ($command->getNomenclatureId()) {
            $nomenclature = $this->eventNomenclatureRepository->find($command->getNomenclatureId());
            if (!$nomenclature) {
                throw new ResourceNotFoundException('Nomenclature not found');
            }
            $event->setNomenclature($nomenclature);
        }

        if ($command->getCustomerId()) {
            $customer = $this->customerRepository->find($command->getCustomerId());
            if (!$customer instanceof Customer) {
                throw new DomainException(sprintf('Customer not found %s', $command->getCustomerId()));
            }
            $event->setCustomer($customer);
        }

        if ($command->getContremarqueId()) {
            $contremarque = $this->contremarqueRepository->find($command->getContremarqueId());
            if ($contremarque) {
                $event->setContremarque($contremarque);
            }
        }

        if ($command->getEventDate()) {
            $eventDate = new DateTimeImmutable($command->getEventDate());
            $event->setEventDate($eventDate);
        }

        if (true === $command->getDisabledReminder()) {
            $event->setReminderDisabled(true);
            $event->setNextReminderDeadline(null);
        } else {
            if ($command->getNextReminderDeadline()) {
                $nextReminderDeadline = new DateTimeImmutable($command->getNextReminderDeadline());
                $event->setNextReminderDeadline($nextReminderDeadline);
            }

            if (null !== $command->getDisabledReminder()) {
                $event->setReminderDisabled($command->getDisabledReminder());
            }
        }

        if ($command->getCommentaire()) {
            $event->setCommentaire($command->getCommentaire());
        }

        if ($command->getPeoplePresent()) {
            $event->setPeoplePresent($command->getPeoplePresent());
        }

        $this->eventRepository->flush();

        return new EventResponse($event);
    }
}
