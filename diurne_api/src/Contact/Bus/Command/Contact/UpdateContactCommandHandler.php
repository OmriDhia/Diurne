<?php

declare(strict_types=1);

namespace App\Contact\Bus\Command\Contact;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\DuplicateValidationResourceException;
use App\Common\Exception\ResourceNotFoundException;
use App\Contact\Entity\Contact;
use App\Contact\Entity\Customer;
use App\Contact\Repository\ContactRepository;
use App\Contact\Repository\CustomerRepository;
use App\Setting\Repository\DiscountRuleRepository;
use App\User\Entity\Gender;
use App\User\Entity\User;
use App\User\Repository\GenderRepository;
use App\User\Repository\ProfileRepository;
use App\User\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UpdateContactCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly ContactRepository           $contactRepository,
        private readonly CustomerRepository          $customerRepository,
        private readonly UserRepository              $userRepository,
        private readonly UserPasswordHasherInterface $hasher,
        private readonly DiscountRuleRepository      $discountRuleRepository,
        private readonly ProfileRepository           $profileRepository,
        private readonly GenderRepository            $genderRepository,
    ) {}

    public function __invoke(UpdateContactCommand $command): UpdateContactResponse|ResourceNotFoundException
    {
        $contact = $this->contactRepository->find((int)$command->getContactId());
        if (!empty($command->getCustomerId())) {
            $customer = $this->customerRepository->find((int)$command->getCustomerId());
            if (!$customer instanceof Customer) {
                throw new ResourceNotFoundException();
            }
            if ('Particulier (Client)' == $customer->getCustomerGroup()->getName()) {
                throw new DuplicateValidationResourceException('Customer is a "Particulier (Client)"');
            }
        }
        if (!$contact instanceof Contact) {
            return new ResourceNotFoundException();
        }
        $user = $contact->getContactInformationSheet()->getUser();
        if (!empty($command->getFirstName())) {
            $user->setLastname($command->getFirstName());
        }
        if (!empty($command->getLastName())) {
            $user->setFirstname($command->getLastName());
        }
        if (!empty($command->getEmail())) {
            $existingUser = $this->userRepository->findByEmail($command->getEmail());
            if ($existingUser instanceof User && $existingUser->getId() !== $user->getId()) {
                $contactWithSameUser = $this->contactRepository->findOneByUser($existingUser);
                if ($contactWithSameUser instanceof Contact) {
                    throw new DuplicateValidationResourceException('There is a contact with same user');
                }
            }
            $user->setEmail($command->getEmail());
        }
        if (!empty($command->getGenderId())) {
            $gender = $this->genderRepository->find((int)$command->getGenderId());
            if ($gender instanceof Gender) {
                $user->setGender($gender);
            }
            $this->contactRepository->persist($user);
        }

        $contactInformation = $contact->getContactInformationSheet();
        if (!empty($command->getGenderId())) {
            $gender = $this->genderRepository->find((int)$command->getGenderId());
            $contactInformation->setGender($gender);
        }
        if (!empty($command->getFirstName())) {
            $contactInformation->setFirstname($command->getFirstName());
        }
        if (!empty($command->getLastName())) {
            $contactInformation->setLastname($command->getLastName());
        }
        if (!empty($command->getEmail())) {
            $contactInformation->setEmail($command->getEmail());
        }
        if (!empty($command->getPhone())) {
            $contactInformation->setPhone($command->getPhone());
        }
        if (!empty($command->getMobilePhone())) {
            $contactInformation->setMobilePhone($command->getMobilePhone());
        }
        if (!empty($command->getFax())) {
            $contactInformation->setFax($command->getFax());
        }

        $contactInformation->setUser($user);
        $this->contactRepository->persist($user);
        $this->contactRepository->persist($contactInformation);

        $contact->setContactInformationSheet($contactInformation);

        $contact->setMailing($command->getMailing());

        $contact->setMailingWithCaligraphie($command->getMailingWithCalligraphie());
        $this->contactRepository->persist($contact);
        $this->contactRepository->flush();
        if (!empty($command->getCustomerId())) {
            $customer = $this->customerRepository->find((int)$command->getCustomerId());
            $customer->addContact($contact);
            $this->customerRepository->persist($customer);
            $this->customerRepository->flush();
        }

        return new UpdateContactResponse(
            $contact->getId(),
            $user->getId(),
            $user->getFirstname(),
            $user->getLastname(),
            $user->getEmail(),
            !empty($user->getGender()) ? $user->getGender()->getId() : '',
            $contact->isMailing(),
            $contact->isMailingWithCaligraphie(),
            !empty($contact->getContactInformationSheet()) ? $contact->getContactInformationSheet()->getPhone() : '',
            !empty($contact->getContactInformationSheet()) ? $contact->getContactInformationSheet()->getMobilePhone() : '',
            !empty($contact->getContactInformationSheet()) ? $contact->getContactInformationSheet()->getFax() : '',
            $customer->getId(),
        );
    }
}
