<?php

declare(strict_types=1);

namespace App\Event\Bus\Query\GetEventsByContremarqueId;

use Exception;
use App\Common\Bus\Query\QueryHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Contremarque\Entity\Contremarque;
use App\Contremarque\Repository\ContremarqueRepository;
use App\Event\Entity\Event;
use App\Event\Entity\PeoplePresent;
use App\Event\Repository\EventRepository;
use App\Event\Repository\PeoplePresentRepository;
use Psr\Log\LoggerInterface;

/**
 * Handles the query to retrieve events by Contremarque ID.
 */
final readonly class GetEventsByContremarqueIdQueryHandler implements QueryHandler
{
    public function __construct(
        private EventRepository $eventRepository,
        private ContremarqueRepository $contremarqueRepository,
        private PeoplePresentRepository $peoplePresentRepository,
        private LoggerInterface $logger
    ) {}

    /**
     * @throws ResourceNotFoundException
     */
    public function __invoke(GetEventsByContremarqueIdQuery $query): GetEventsByContremarqueIdResponse
    {
        try {
            $contremarque = $this->fetchContremarque((int)$query->contremarqueId);
            $events = $this->fetchEvents($contremarque);
            $eventData = $this->transformEvents($events);

            $this->logger->info('Successfully retrieved events for Contremarque', [
                'contremarqueId' => $contremarque->getId(),
                'eventCount' => count($eventData),
            ]);

            return new GetEventsByContremarqueIdResponse(array_reverse($eventData));
        } catch (ResourceNotFoundException $e) {
            $this->logger->warning('Contremarque not found', [
                'contremarqueId' => (int)$query->contremarqueId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        } catch (Exception $e) {
            $this->logger->error('Failed to retrieve events for Contremarque', [
                'contremarqueId' => (int)$query->contremarqueId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * @throws ResourceNotFoundException
     */
    private function fetchContremarque(int $contremarqueId): Contremarque
    {
        $contremarque = $this->contremarqueRepository->find($contremarqueId);
        if (!$contremarque instanceof Contremarque) {
            throw new ResourceNotFoundException(sprintf('Contremarque with ID %d not found', $contremarqueId));
        }

        return $contremarque;
    }

    /**
     * @return Event[]
     */
    private function fetchEvents(Contremarque $contremarque): array
    {
        $events = $this->eventRepository->findBy(['contremarque' => $contremarque]);
        return $events ?: [];
    }

    /**
     * @param Event[] $events
     * @return array<int, array<string, mixed>>
     */
    private function transformEvents(array $events): array
    {
        if (empty($events)) {
            return [];
        }

        // Fetch all PeoplePresent records for the events in one query
        $eventIds = array_map(fn(Event $event): int|null => $event->getId(), $events);
        $peoplePresentRecords = $this->peoplePresentRepository->findByEventIds($eventIds);

        // Group PeoplePresent records by event ID
        $peoplePresentByEvent = $this->groupPeoplePresentByEvent($peoplePresentRecords);

        $eventData = [];
        foreach ($events as $event) {
            $eventId = $event->getId();
            $peoplePresents = $this->transformPeoplePresent($peoplePresentByEvent[$eventId] ?? []);

            $eventData[] = [
                'event_id' => $eventId,
                'contremarque_id' => $event->getContremarque()?->getId(),
                'nomenclature' => $event->getNomenclature()?->toArray() ?? [],
                'next_reminder_deadline' => $event->getNextReminderDeadline(),
                'reminder_disabled' => $event->isReminderDisabled(),
                'commentaire' => $event->getCommentaire(),
                'people_present' => $peoplePresents,
                'event_date' => $event->getEventDate(),
            ];
        }

        return $eventData;
    }

    /**
     * @param PeoplePresent[] $peoplePresentRecords
     *
     * @return PeoplePresent[][]
     *
     * @psalm-return array<''|int, non-empty-list<PeoplePresent>>
     */
    private function groupPeoplePresentByEvent(array $peoplePresentRecords): array
    {
        $peoplePresentByEvent = [];
        foreach ($peoplePresentRecords as $record) {
            $eventId = $record->getEvent()->getId();
            $peoplePresentByEvent[$eventId][] = $record;
        }

        return $peoplePresentByEvent;
    }

    /**
     * @param PeoplePresent[] $peoplePresentRecords
     *
     * @return (int|null)[][]
     *
     * @psalm-return array{contacts: list{0?: int|null,...}, users: list{0?: int|null,...}, others: list{0?: int|null,...}}
     */
    private function transformPeoplePresent(array $peoplePresentRecords): array
    {
        $peoplePresents = [
            'contacts' => [],
            'users' => [],
            'others' => [],
        ];

        foreach ($peoplePresentRecords as $record) {
            $resourceId = $record->getResourceId();
            $category = match ($record->getResource()) {
                'Contact' => 'contacts',
                'User' => 'users',
                default => 'others',
            };

            $peoplePresents[$category][] = $resourceId;
        }

        return $peoplePresents;
    }
}
