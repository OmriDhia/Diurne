<?php

declare(strict_types=1);

namespace App\Contact\Bus\Command\DeleteContact;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Contact\Entity\Contact;
use App\Contact\Repository\ContactRepository;
use App\User\Repository\UserRepository;

class DeleteContactCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly ContactRepository $contactRepository,
        private readonly UserRepository $userRepository
    ) {
    }

    public function __invoke(DeleteContactCommand $command): DeleteContactResponse
    {
        $contact = $this->contactRepository->find((int) $command->getContactId());

        if (!$contact instanceof Contact) {
            throw new ResourceNotFoundException();
        }
        $contactInformationSheet = $contact->getContactInformationSheet();
        $user = $contact->getContactInformationSheet()->getUser();
        $contactInformationSheet->setUser(null);
        $this->contactRepository->persist($contactInformationSheet);
        $this->contactRepository->flush();
        $this->contactRepository->remove($user);
        $contact->setContactInformationSheet(null);
        $contact->setCustomer(null);
        $this->contactRepository->persist($contact);
        $this->contactRepository->flush();
        $this->contactRepository->remove($contactInformationSheet);
        $this->contactRepository->remove($contact);

        return new DeleteContactResponse($command->getContactId());
    }
}
