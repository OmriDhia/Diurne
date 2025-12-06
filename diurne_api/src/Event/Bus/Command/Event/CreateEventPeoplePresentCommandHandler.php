<?php

declare(strict_types=1);

namespace App\Event\Bus\Command\Event;

use App\Common\Bus\Command\CommandHandler;
use App\Contact\Repository\ContactRepository;
use App\Event\Entity\PeoplePresent;
use App\Event\Repository\EventRepository;
use App\Event\Repository\PeoplePresentRepository;
use App\User\Repository\UserRepository;

class CreateEventPeoplePresentCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly EventRepository $eventRepository,
        private readonly PeoplePresentRepository $peoplePresentRepository,
        private readonly UserRepository $userRepository,
        private readonly ContactRepository $contactRepository
    ) {
    }

    public function __invoke(CreateEventPeoplePresentCommand $command): EventPeoplePresentResponse
    {
        $event = $this->eventRepository->find((int) $command->getEventId());
        if (!empty($command->getContacts())) {
            foreach ($command->getContacts() as $contactId) {
                $exit = $this->peoplePresentRepository->findOneBy(['event' => $event, 'resourceId' => $contactId, 'resource' => 'Contact']);
                if ($exit instanceof PeoplePresent) {
                    continue;
                }
                $peoplePresent = new PeoplePresent();
                $peoplePresent->setEvent($event);
                $peoplePresent->setResource('Contact');
                $peoplePresent->setResourceId($contactId);
                $this->peoplePresentRepository->persist($peoplePresent);
            }
        }
        if (!empty($command->getUsers())) {
            foreach ($command->getUsers() as $userId) {
                $exit = $this->peoplePresentRepository->findOneBy(['event' => $event, 'resourceId' => $userId, 'resource' => 'User']);
                if ($exit instanceof PeoplePresent) {
                    continue;
                }
                $peoplePresent = new PeoplePresent();
                $peoplePresent->setEvent($event);
                $peoplePresent->setResource('User');
                $peoplePresent->setResourceId($userId);
                $this->peoplePresentRepository->persist($peoplePresent);
            }
        }

        $this->peoplePresentRepository->flush();

        return new EventPeoplePresentResponse($event);
    }
}
