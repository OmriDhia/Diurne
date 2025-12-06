<?php

declare(strict_types=1);

namespace App\Contact\Bus\Command\Contact;

use DomainException;
use DateTimeImmutable;
use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\DuplicateValidationResourceException;
use App\Common\Exception\ValidationException;
use App\Contact\Entity\Contact;
use App\Contact\Entity\ContactInformationSheet;
use App\Contact\Entity\Customer;
use App\Contact\Entity\CustomerGroup;
use App\Contact\Entity\IntermediaryInformationSheet;
use App\Contact\Repository\ContactOriginRepository;
use App\Contact\Repository\ContactRepository;
use App\Contact\Repository\CustomerGroupRepository;
use App\Contact\Repository\CustomerRepository;
use App\Contact\Repository\IntermediaryTypeRepository;
use App\Event\Entity\Event;
use App\Event\Repository\EventNomenclatureRepository;
use App\Event\Repository\EventRepository;
use App\Setting\Repository\DiscountRuleRepository;
use App\Setting\Repository\LanguageRepository;
use App\User\Entity\Gender;
use App\User\Entity\Profile;
use App\User\Entity\User;
use App\User\Repository\GenderRepository;
use App\User\Repository\ProfileRepository;
use App\User\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateCustomerCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly CustomerRepository          $customerRepository,
        private readonly UserRepository              $userRepository,
        private readonly CustomerGroupRepository     $customerGroupRepository,
        private readonly DiscountRuleRepository      $discountRuleRepository,
        private readonly EventNomenclatureRepository $eventNomenclatureRepository,
        private readonly EventRepository             $eventRepository,
        private readonly LanguageRepository          $languageRepository,
        private readonly EntityManagerInterface      $entityManager,
        private readonly IntermediaryTypeRepository  $intermediaryTypeRepository,
        private readonly ContactRepository           $contactRepository,
        private readonly UserPasswordHasherInterface $hasher,
        private readonly ProfileRepository           $profileRepository,
        private readonly GenderRepository            $genderRepository,
        private readonly ContactOriginRepository     $contactOriginRepository,
    )
    {
    }

    public function __invoke(CreateCustomerCommand $command): CreateCustomerResponse
    {
        $customer = $this->customerRepository->findOneByCode($command->getCode());

        if ($customer instanceof Customer) {
            throw new DuplicateValidationResourceException('There is a customer with same code');
        }
        if (empty($command->getCustomerGroupId())) {
            throw new ValidationException(['Customer group is required.']);
        }

        $customerGroup = $this->customerGroupRepository->find((int)$command->getCustomerGroupId());

        if (!$customerGroup instanceof CustomerGroup) {
            throw new ValidationException(['Customer group is required.']);
        }
        $customer = new Customer();
        $customer->setCustomerGroup($customerGroup);
        do {
            $customer->setCode($command->getCode());
        } while ($this->customerRepository->findOneByCode($command->getCode()));
        $customer->setActive(true);
        if ('Particulier (Client)' != $customerGroup->getName()) {
            $customer->setSocialReason($command->getSocialReason());
            $customer->setTvaCe($command->getTvaCe());
        }
        if (!empty($command->getContactOriginId())) {
            $origin = $this->contactOriginRepository->find($command->getContactOriginId());
            if (!$origin) {
                throw new DomainException(sprintf('Invalid contact_origin_id=%d (no matching ContactOrigin row).', $command->getContactOriginId()));
            }
            $customer->setContactOrigin($origin);
        }
        $customer->setCommentaire($command->getCommentaire());
        if ('Particulier (Client)' == $customerGroup->getName()) {
            if (empty($command->getLastname())) {
                throw new ValidationException(['Customer name is required.']);
            }
            if (!empty($command->getEmail())) {
                $email = $command->getEmail();
                $user = $this->userRepository->findByEmail($email);
            } else {
                $email = 'auto-' . uniqid() . '@yopmail.com';
                $user = $this->userRepository->findByEmail($email);
            }
            $firstName = $command->getFirstName();

            if ($user instanceof User) {
                $contact = $this->contactRepository->findOneByUser($user);
                if ($contact instanceof Contact) {
                    throw new DuplicateValidationResourceException('There is a contact with same user');
                }
            }

            if (!$user instanceof User) {
                $user = new User();
                $user->setLastname($command->getLastname());
                $user->setFirstname($firstName);
                $user->setEmail($email);
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

            if (empty($command->getGenderId()) || empty($gender)) {
                throw new ValidationException(['Gender is required.']);
            }
            if ($gender instanceof Gender) {
                $user->setGender($gender);
                $contactInformation->setGender($gender);
            }


            $contactInformation->setFirstname($firstName);
            $contactInformation->setLastname($command->getLastName());
            $contactInformation->setEmail($email);
            $contactInformation->setPhone($command->getPhone());
            $contactInformation->setMobilePhone($command->getMobilePhone());
            $contactInformation->setFax($command->getFax());
            $contactInformation->setUser($user);
            $this->contactRepository->persist($contactInformation);
            $customer->setContactInformationSheet($contactInformation);
            $contact = new Contact();
            $contact->setContactInformationSheet($contactInformation);
            $contact->setMailing(true);
            $this->contactRepository->persist($contact);
            $this->contactRepository->flush();

            $customer->addContact($contact);
            $this->customerRepository->persist($customer);
            $this->customerRepository->flush();
            $nomenclature = $this->eventNomenclatureRepository->findBySubject('Creation du contact');

            $event = new Event();
            $event->setNomenclature($nomenclature);
            $event->setCustomer($customer);
            $event->setEventDate(new DateTimeImmutable());
            $event->setCommentaire($command->getFirstName() . ' ' . $command->getLastName());
            $this->eventNomenclatureRepository->persist($event);
            $this->eventRepository->flush();
        }
        if (!empty($command->getDiscountTypeId())) {
            $discountType = $this->discountRuleRepository->find($command->getDiscountTypeId());
            $customer->setDiscountRule($discountType);
        } else {
            $customer->setDiscountRule(null);
        }
        if (!empty($command->getWebsite())) {
            $customer->setWebSite($command->getWebsite());
        }
        if (!empty($command->getMailingLanguageId())) {
            $language = $this->languageRepository->find((int)$command->getMailingLanguageId());
            $customer->setMailingLanguage($language);
        }
        if ($command->isAgent()) {
            $customer->setIntermediary(true);
            $intermediaryInformationSheet = new IntermediaryInformationSheet();
            $intermediaryType = $this->intermediaryTypeRepository->findOneBy(['name' => 'Agent']);
            $intermediaryInformationSheet->setIntermediaryType($intermediaryType);
            $this->entityManager->persist($intermediaryInformationSheet);
            $customer->setIntermediaryInformationSheet($intermediaryInformationSheet);
        } else {
            $customer->setIntermediary(false);
        }

        $nomenclature = $this->eventNomenclatureRepository->findBySubject('Creation du Client');
        $event = new Event();
        $event->setNomenclature($nomenclature);
        $event->setCustomer($customer);
        $event->setEventDate(new DateTimeImmutable());
        $event->setCommentaire($command->getFirstName() . ' ' . $command->getLastName());
        $this->customerRepository->persist($customer);
        $this->customerRepository->flush();
        $this->eventRepository->persist($event);
        $this->eventRepository->flush();
        return new CreateCustomerResponse(
            $customer->getId(),
            $customer->getCode(),
            $customer->getSocialReason(),
            $customer->getTvaCe(),
            $customer->getWebsite(),
            !empty($customer->getContactInformationSheet()) ? $customer->getContactInformationSheet()->getFirstname() : '',
            !empty($customer->getContactInformationSheet()) ? $customer->getContactInformationSheet()->getLastname() : '',
            !empty($customer->getContactInformationSheet()) ? $customer->getContactInformationSheet()->getEmail() : '',
            !empty($customer->getContactInformationSheet()) ? $customer->getContactInformationSheet()->getPhone() : '',
            !empty($customer->getContactInformationSheet()) ? $customer->getContactInformationSheet()->getMobilePhone() : '',
            (bool)$customer->isIntermediary(),
            !empty($customer->getIntermediaryInformationSheet()) ? $customer->getIntermediaryInformationSheet()->getIntermediaryType()->getName() : '',
            !empty($customer->getDiscountRule()) ? $customer->getDiscountRule()->getId() : null,
            !empty($customer->getCustomerGroup()) ? $customer->getCustomerGroup()->getId() : null,
            !empty($customer->getMailingLanguage()) ? $customer->getMailingLanguage()->getId() : null,
            $customer->getContactOrigin()->getId(),
            $customer->getCommentaire(),
        );
    }
}
