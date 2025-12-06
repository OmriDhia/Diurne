<?php

declare(strict_types=1);

namespace App\Contact\Bus\Command\Contact;

use DateTimeImmutable;
use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\DuplicateValidationResourceException;
use App\Common\Exception\ResourceNotFoundException;
use App\Contact\Entity\Contact;
use App\Contact\Entity\ContactInformationSheet;
use App\Contact\Entity\Customer;
use App\Contact\Repository\ContactRepository;
use App\Contact\Repository\CustomerRepository;
use App\Event\Entity\Event;
use App\Event\Repository\EventNomenclatureRepository;
use App\Event\Repository\EventRepository;
use App\Setting\Repository\DiscountRuleRepository;
use App\User\Entity\Gender;
use App\User\Entity\Profile;
use App\User\Entity\User;
use App\User\Repository\GenderRepository;
use App\User\Repository\ProfileRepository;
use App\User\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateContactCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly ContactRepository           $contactRepository,
        private readonly CustomerRepository          $customerRepository,
        private readonly UserRepository              $userRepository,
        private readonly UserPasswordHasherInterface $hasher,
        private readonly DiscountRuleRepository      $discountRuleRepository,
        private readonly ProfileRepository           $profileRepository,
        private readonly GenderRepository            $genderRepository,
        private readonly EventRepository             $eventRepository,
        private readonly EventNomenclatureRepository $eventNomenclatureRepository,
    ) {}

    public function __invoke(CreateContactCommand $command): CreateContactResponse
    {
        if (!empty($command->getCustomerId())) {
            $customer = $this->customerRepository->find((int)$command->getCustomerId());
            if (!$customer instanceof Customer) {
                throw new ResourceNotFoundException();
            }
            if ('Particulier (Client)' == $customer->getCustomerGroup()->getName()) {
                throw new DuplicateValidationResourceException('Customer is a Particulier');
            }
        }
        $user = $this->userRepository->findByEmail($command->getEmail());
        if ($user instanceof User) {
            $contact = $this->contactRepository->findOneByUser($user);
            if ($contact instanceof Contact) {
                throw new DuplicateValidationResourceException('There is a contact with same user');
            }
        }

        if (!$user instanceof User) {
            $user = new User();
            $user->setLastname($command->getLastName());
            $user->setFirstname($command->getFirstName());
            if (!empty($command->getEmail())) {
                $user->setEmail($command->getEmail());
            } else {
                $email = 'auto-' . uniqid() . '@yopmail.com';
                $user->setEmail($email);
            }
            $user->setRoles(['ROLE_USER']);
            $password = $this->hasher->hashPassword($user, '123@123@1234');
            $user->setPassword($password);
            $profile = $this->profileRepository->findOneByName('Client');
            if ($profile instanceof Profile) {
                $user->setProfile($profile);
            }
            $gender = $this->genderRepository->find((int)$command->getGenderId());
            if ($gender instanceof Gender) {
                $user->setGender($gender);
            }
            $this->contactRepository->persist($user);
        }
        $contactInformation = new ContactInformationSheet();
        $gender = $this->genderRepository->find((int)$command->getGenderId());
        if ($gender instanceof Gender) {
            $user->setGender($gender);
        }
        $contactInformation->setGender($gender);
        $contactInformation->setFirstname($command->getFirstName());
        $contactInformation->setLastname($command->getLastName());
        if (!empty($command->getEmail())) {
            $contactInformation->setEmail($command->getEmail());
        } else {
            $email = 'auto-' . uniqid() . '@yopmail.com';
            $contactInformation->setEmail($email);
        }
        $contactInformation->setPhone($command->getPhone());
        $contactInformation->setMobilePhone($command->getMobilePhone());
        $contactInformation->setFax($command->getFax());
        $contactInformation->setUser($user);
        $this->contactRepository->persist($contactInformation);

        $contact = new Contact();
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
            $nomenclature = $this->eventNomenclatureRepository->findBySubject('Creation du contact');

            $event = new Event();
            $event->setNomenclature($nomenclature);
            $event->setCustomer($customer);
            $event->setEventDate(new DateTimeImmutable());
            $event->setCommentaire($command->getFirstName() . ' ' . $command->getLastName());
            $this->eventRepository->persist($event);
            $this->eventRepository->flush();
        }

        return new CreateContactResponse(
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
