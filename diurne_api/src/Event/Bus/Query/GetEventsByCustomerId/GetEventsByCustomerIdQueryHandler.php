<?php

declare(strict_types=1);

namespace App\Event\Bus\Query\GetEventsByCustomerId;

use App\Common\Bus\Query\QueryHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Contact\Repository\CustomerRepository;
use App\Event\Repository\EventRepository;
use App\Event\Repository\PeoplePresentRepository;

final readonly class GetEventsByCustomerIdQueryHandler implements QueryHandler
{
    public function __construct(
        private EventRepository $eventRepository,
        private CustomerRepository $customerRepository,
        private PeoplePresentRepository $peoplePresentRepository
    ) {
    }

    public function __invoke(GetEventsByCustomerIdQuery $query): GetEventsByCustomerIdResponse
    {
        // Retrieve the customer by ID
        $customer = $this->customerRepository->findOneById((int) $query->customerId());

        if (null === $customer) {
            throw new ResourceNotFoundException('Customer not found');
        }

        $customerEventsData = [];

        // Iterate over the customer's events
        foreach ($customer->getEvents() as $event) {
            // Apply the contremarque filter if provided
            if (null !== $query->contremarqueId()) {
                if (null === $event->getContremarque() || $event->getContremarque()->getId() !== (int) $query->contremarqueId()) {
                    continue; // Skip events that don't match the filter
                }
            }
            $peoplePresentCollection = $this->peoplePresentRepository->findBy(['event' => $event]);
            $peoplePresents = [];
            if ($peoplePresentCollection) {
                foreach ($peoplePresentCollection as $peoplePresent) {
                    if ('Contact' === $peoplePresent->getResource()) {
                        $peoplePresents['contacts'][] = $peoplePresent->getResourceId();
                    } elseif ('User' === $peoplePresent->getResource()) {
                        $peoplePresents['users'][] = $peoplePresent->getResourceId();
                    } else {
                        $peoplePresents['others'][] = $peoplePresent->getResourceId();
                    }
                }
            }

            // Collect event details
            $customerEventsData[] = [
                'event_id' => $event->getId(),
                'contremarque_id' => !empty($event->getContremarque()) ? $event->getContremarque()->getId() : null,
                'nomenclature' => !empty($event->getNomenclature()) ? $event->getNomenclature()->toArray() : [],
                'next_reminder_deadline' => $event->getNextReminderDeadline(),
                'reminder_disabled' => $event->isReminderDisabled(),
                'commentaire' => $event->getCommentaire(),
                'people_present' => $peoplePresents,
                'event_date' => $event->getEventDate(),
            ];
        }

        // Return the filtered and reversed event data
        return new GetEventsByCustomerIdResponse(array_reverse($customerEventsData));
    }
}
