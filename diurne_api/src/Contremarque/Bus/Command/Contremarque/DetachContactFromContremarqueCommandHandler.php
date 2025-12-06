<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\Contremarque;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Contact\Entity\Contact;
use App\Contact\Repository\ContactRepository;
use App\Contremarque\Entity\Contremarque;
use App\Contremarque\Entity\ContremarqueContact;
use App\Contremarque\Repository\ContremarqueContactRepository;
use App\Contremarque\Repository\ContremarqueRepository;

class DetachContactFromContremarqueCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly ContremarqueRepository $contremarqueRepository,
        private readonly ContactRepository $contactRepository,
        private readonly ContremarqueContactRepository $contremarqueContactRepository
    ) {
    }

    public function __invoke(DetachContactFromContremarqueCommand $command): DetachContactFromContremarqueResponse
    {
        $contremarque = $this->contremarqueRepository->find((int) $command->getContremarqueId());
        if (!$contremarque instanceof Contremarque) {
            throw new ResourceNotFoundException();
        }
        $contact = $this->contactRepository->find((int) $command->getContactId());
        if (!$contact instanceof Contact) {
            throw new ResourceNotFoundException();
        }
        $contremarqueContact = $this->contremarqueContactRepository->findOneBy(
            [
                'contremarque' => $contremarque,
                'contact' => $contact,
            ]
        );
        if (!$contremarqueContact instanceof ContremarqueContact) {
            throw new ResourceNotFoundException();
        }
        $this->contremarqueContactRepository->remove($contremarqueContact);

        return new DetachContactFromContremarqueResponse(
            (int) $command->getContremarqueId(),
            (int) $command->getContactId(),
        );
    }
}
