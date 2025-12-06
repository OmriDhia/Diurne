<?php

// src/Contremarque/EventListener/ContremarqueContactCurrentUpdatedListener.php

namespace App\Contremarque\EventListener;

use App\Contremarque\Event\ContremarqueContactCurrentUpdatedEvent;
use App\Contremarque\Repository\ContremarqueContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\AsEventListener;

#[AsEventListener]
class ContremarqueContactCurrentUpdatedListener
{
    public function __construct(
        private readonly ContremarqueContactRepository $contremarqueContactRepository,
        private readonly EntityManagerInterface $entityManager
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            ContremarqueContactCurrentUpdatedEvent::class => 'onCurrentContactUpdated',
        ];
    }

    public function onCurrentContactUpdated(ContremarqueContactCurrentUpdatedEvent $event): void
    {
        $contremarque = $event->getContremarque();
        $currentContact = $event->getContremarqueContact();

        $currentContacts = $this->contremarqueContactRepository->findBy([
            'contremarque' => $contremarque,
            'current' => true,
        ]);

        foreach ($currentContacts as $contact) {
            if ($contact !== $currentContact) {
                $contact->setCurrent(false);
                $this->entityManager->persist($contact);
            }
        }

        $this->entityManager->flush();
    }
}
