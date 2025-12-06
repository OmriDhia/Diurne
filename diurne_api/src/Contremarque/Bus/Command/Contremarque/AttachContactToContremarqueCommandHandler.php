<?php

// src/Contremarque/Bus/Command/Contremarque/AttachContactToContremarqueCommandHandler.php

namespace App\Contremarque\Bus\Command\Contremarque;

use InvalidArgumentException;
use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Contact\Entity\Contact;
use App\Contact\Repository\ContactRepository;
use App\Contremarque\Entity\Contremarque;
use App\Contremarque\Entity\ContremarqueContact;
use App\Contremarque\Event\ContremarqueContactCurrentUpdatedEvent;
use App\Contremarque\Repository\ContremarqueContactRepository; // Updated namespace
use App\Contremarque\Repository\ContremarqueRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class AttachContactToContremarqueCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly ContremarqueRepository $contremarqueRepository,
        private readonly ContactRepository $contactRepository,
        private readonly ContremarqueContactRepository $contremarqueContactRepository,
        private readonly EventDispatcherInterface $eventDispatcher
    ) {
    }

    public function __invoke(AttachContactToContremarqueCommand $command): AttachContactToContremarqueResponse
    {
        $contremarque = $this->findContremarque($command->getContremarqueId());
        $contact = $this->findContact($command->getContactId());
        if ($contremarque->getCustomer() !== $contact->getCustomer()) {
            throw new InvalidArgumentException('The contremarque must belong to the same customer as the contact.');
        }
        $contremarqueContact = $this->contremarqueContactRepository->findOneBy([
            'contremarque' => $contremarque,
            'contact' => $contact,
        ]);

        if (!$contremarqueContact instanceof ContremarqueContact) {
            $contremarqueContact = new ContremarqueContact();
            $contremarqueContact->setContact($contact);
            $contremarqueContact->setContremarque($contremarque);
        }

        if ($command->getCurrent()) {
            $this->eventDispatcher->dispatch(
                new ContremarqueContactCurrentUpdatedEvent($contremarque, $contremarqueContact)
            );
        }

        $contremarqueContact->setCurrent($command->getCurrent());
        $this->contremarqueContactRepository->persist($contremarqueContact);
        $this->contremarqueContactRepository->flush();

        return new AttachContactToContremarqueResponse(
            $contremarque->getId(),
            $contact->getId(),
            $command->getCurrent()
        );
    }

    private function findContremarque(int $id): Contremarque
    {
        $contremarque = $this->contremarqueRepository->find((int) $id);
        if (!$contremarque instanceof Contremarque) {
            throw new ResourceNotFoundException('Contremarque not found');
        }

        return $contremarque;
    }

    private function findContact(int $id): Contact
    {
        $contact = $this->contactRepository->find((int) $id);
        if (!$contact instanceof Contact) {
            throw new ResourceNotFoundException('Contact not found');
        }

        return $contact;
    }
}
