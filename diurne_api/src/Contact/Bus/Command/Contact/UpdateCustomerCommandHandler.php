<?php

declare(strict_types=1);

namespace App\Contact\Bus\Command\Contact;

use DomainException;
use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\DuplicateValidationResourceException;
use App\Common\Exception\ResourceNotFoundException;
use App\Common\Exception\ValidationException;
use App\Contact\Entity\Contact;
use App\Contact\Entity\Customer;
use App\Contact\Entity\IntermediaryInformationSheet;
use App\Contact\Repository\ContactOriginRepository;
use App\Contact\Repository\ContactRepository;
use App\Contact\Repository\CustomerGroupRepository;
use App\Contact\Repository\CustomerRepository;
use App\Contact\Repository\IntermediaryTypeRepository;
use App\Event\Repository\EventNomenclatureRepository;
use App\Event\Repository\EventRepository;
use App\Setting\Repository\DiscountRuleRepository;
use App\Setting\Repository\LanguageRepository;
use App\User\Entity\Gender;
use App\User\Entity\User;
use App\User\Repository\GenderRepository;
use App\User\Repository\ProfileRepository;
use App\User\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UpdateCustomerCommandHandler implements CommandHandler
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
        private readonly ContactOriginRepository     $contactOriginRepository
    )
    {
    }

    public function __invoke(UpdateCustomerCommand $command): UpdateCustomerResponse
    {
        $customer = $this->customerRepository->find((int)$command->getCustomerId());

        if (!$customer instanceof Customer) {
            throw new ResourceNotFoundException();
        }

        if (!empty($command->getWebsite())) {
            $customer->setWebSite($command->getWebsite());
        }

        $customer->setActive(true);
        $this->customerRepository->persist($customer);

        if (!empty($command->getDiscountTypeId())) {
            $discountType = $this->discountRuleRepository->find((int)$command->getDiscountTypeId());
            $customer->setDiscountRule($discountType);
        }
        if (!empty($command->getCustomerGroupId())) {
            $customerGroup = $this->customerGroupRepository->find((int)$command->getCustomerGroupId());
            $customer->setCustomerGroup($customerGroup);
        }
        if (!empty($command->getContactOriginId())) {
            $origin = $this->contactOriginRepository->find($command->getContactOriginId());
            if (!$origin) {
                throw new DomainException(sprintf('Invalid contact_origin_id=%d (no matching ContactOrigin row).', $command->getContactOriginId()));
            }
            $customer->setContactOrigin($origin);
        }
        $customer->setCommentaire($command->getCommentaire());
        $this->entityManager->persist($customer);
        $this->entityManager->flush();

        if ('Particulier (Client)' != $customer->getCustomerGroup()->getName()) {
            if (!empty($command->getSocialReason())) {
                $customer->setSocialReason($command->getSocialReason());
            }
            if (!empty($command->getTvaCe())) {
                $customer->setTvaCe($command->getTvaCe());
            } else {
                $customer->setTvaCe(null);
            }
        }

        if ('Particulier (Client)' == $customer->getCustomerGroup()->getName()) {
            if (empty($command->getGenderId())) {
                throw new ValidationException(['Gender is required.']);
            }

            $contactInformation = $customer->getContactInformationSheet();
            $user = $contactInformation->getUser();

            if (!empty($command->getEmail())) {
                $existingUser = $this->userRepository->findByEmail($command->getEmail());
                if ($existingUser instanceof User && $existingUser->getId() !== $user->getId()) {
                    $contact = $this->contactRepository->findOneByUser($existingUser);
                    if ($contact instanceof Contact) {
                        throw new DuplicateValidationResourceException('There is a contact with same user');
                    }
                }
                $email = $command->getEmail();
            } else {
                $email = $contactInformation->getEmail();
            }
            $user->setEmail($email);
            $contactInformation->setEmail($email);

            if (!empty($command->getFirstName())) {
                $user->setFirstname($command->getFirstName());
                $contactInformation->setFirstname($command->getFirstName());
            }
            if (!empty($command->getLastName())) {
                $user->setLastname($command->getLastName());
                $contactInformation->setLastname($command->getLastName());
            }
            if (!empty($command->getGenderId())) {
                $gender = $this->genderRepository->find((int)$command->getGenderId());
                if ($gender instanceof Gender) {
                    $user->setGender($gender);
                    $contactInformation->setGender($gender);
                }
            }
            if (!empty($command->getPhone())) {
                $contactInformation->setPhone($command->getPhone());
            }
            if (!empty($command->getMobilePhone())) {
                $contactInformation->setMobilePhone($command->getMobilePhone());
            }
            if ($command->getFax()) {
                $contactInformation->setFax($command->getFax());
            }

            $this->entityManager->persist($contactInformation);
            $this->entityManager->persist($user);
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
        $this->customerRepository->persist($customer);
        $this->customerRepository->flush();

        return new UpdateCustomerResponse(
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
