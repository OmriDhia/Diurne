<?php

namespace App\Contact\Command;

use DateTimeImmutable;
use DateTime;
use App\Contact\Entity\Address;
use App\Contact\Entity\AddressType;
use App\Contact\Entity\AttributionStatus;
use App\Contact\Entity\BankInformationSheet;
use App\Contact\Entity\Contact;
use App\Contact\Entity\ContactCommercialHistory;
use App\Contact\Entity\ContactInformationSheet;
use App\Contact\Entity\Customer;
use App\Contact\Entity\CustomerGroup;
use App\Contact\Entity\CustomerIntermediaryHistory;
use App\Contact\Entity\IntermediaryInformationSheet;
use App\Contact\Entity\IntermediaryType;
use App\Contact\Repository\AddressTypeRepository;
use App\Contact\Repository\AttributionStatusRepository;
use App\Contact\Repository\CustomerGroupRepository;
use App\Contact\Repository\CustomerRepository;
use App\Contact\Repository\IntermediaryTypeRepository;
use App\Event\Entity\Event;
use App\Event\Entity\EventNomenclature;
use App\Event\Repository\EventNomenclatureRepository;
use App\Setting\Repository\CountryRepository;
use App\Setting\Repository\DiscountRuleRepository;
use App\Setting\Repository\LanguageRepository;
use App\User\Entity\Gender;
use App\User\Entity\User;
use App\User\Repository\GenderRepository;
use App\User\Repository\ProfileRepository;
use App\User\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'pp:run-customer-fixtures'
)]
class ExecuteCustomerFixturesCommand extends Command
{
    protected static $defaultName = 'app:run-customer-fixtures';

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $hasher,
        private readonly DiscountRuleRepository $discountRuleRepository,
        private readonly ProfileRepository $profileRepository,
        private readonly LanguageRepository $languageRepository,
        private readonly CustomerGroupRepository $customerGroupRepository,
        private readonly IntermediaryTypeRepository $intermediaryTypeRepository,
        private readonly AddressTypeRepository $addressTypeRepository,
        private readonly CountryRepository $countryRepository,
        private readonly GenderRepository $genderRepository,
        private readonly EventNomenclatureRepository $eventNomenclatureRepository,
        private readonly UserRepository $userRepository,
        private readonly AttributionStatusRepository $attributionStatusRepository,
        private readonly CustomerRepository $customerRepository
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Step 1: Reset data
        $this->resetData($output);

        // Step 2: Seed data
        $this->seedData($output);

        $output->writeln('Customer data created successfully.');

        return Command::SUCCESS;
    }

    private function resetData(OutputInterface $output): void
    {
        $output->writeln('Resetting existing data...');

        // Disable foreign key checks
        $connection = $this->entityManager->getConnection();
        $connection->executeStatement('SET FOREIGN_KEY_CHECKS = 0');

        // List of entities to reset
        $entitiesToReset = [
            AttributionStatus::class,
            AddressType::class,
            CustomerGroup::class,
            IntermediaryType::class,
            EventNomenclature::class,
            Customer::class,
            Contact::class,
            Address::class,
            ContactInformationSheet::class,
            CustomerIntermediaryHistory::class,
            ContactCommercialHistory::class,
            BankInformationSheet::class,
            IntermediaryInformationSheet::class,
            Event::class,
        ];

        // Delete data from each entity
        foreach ($entitiesToReset as $entityClass) {
            $query = $this->entityManager->createQuery("DELETE FROM $entityClass e");
            $query->execute();
        }
        $commercials = $this->userRepository->getCommercialUser();
        if ($commercials) {
            foreach ($commercials as $commercial) {
                $this->entityManager->remove($commercial);
                $this->entityManager->flush();
            }
        }

        // Re-enable foreign key checks
        $connection->executeStatement('SET FOREIGN_KEY_CHECKS = 1');

        $output->writeln('Data reset complete.');
    }

    private function seedData(OutputInterface $output): void
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '-1');
        $output->writeln('Seeding data...');
        $faker = Factory::create('fr_FR'); // Adjust locale as needed
        $client = $this->profileRepository->findOneBy(['name' => 'Client']);
        $manager = $this->entityManager;

        // Attribution Status
        $status = ['Accepted', 'Refused', 'Pending'];
        foreach ($status as $item) {
            $attributionStatus = new AttributionStatus();
            $attributionStatus->setName($item);
            $manager->persist($attributionStatus);
        }
        $manager->flush();

        // Address Type
        $addressTypes = ['Livraison', 'Facturation', 'Postale (Mailing)'];
        foreach ($addressTypes as $type) {
            $addressType = new AddressType();
            $addressType->setName($type);
            $manager->persist($addressType);
        }
        $manager->flush();

        // Commercial
        $commercialProfile = $this->profileRepository->findOneBy(['name' => 'Commercial']);
        for ($i = 0; $i < 50; ++$i) {
            $user = new User();
            $user->setEmail('commercial-' . $i . '-' . $faker->unique()->safeEmail);
            $user->setPassword($this->hasher->hashPassword($user, '123@123@1234'));
            $user->setRoles(['ROLE_USER']);
            $user->setFirstname($faker->firstName);
            $user->setLastname($faker->lastName);
            $user->setProfile($commercialProfile);
            $manager->persist($user);
        }
        $manager->flush();

        // Customer Group
        $groups = [
            'Particulier (Client)',
            'Entreprise (Client)',
            'Inactif (décès, faillite, doublon)',
            'Journaliste',
            'Hotel',
            'Fournisseur/Concurrent/Confrère',
            'Décorateur/Show-room',
            'Déco/Show-room en Nom propre',
        ];
        foreach ($groups as $group) {
            $customerGroup = new CustomerGroup();
            $customerGroup->setName($group);
            $manager->persist($customerGroup);
        }
        $manager->flush();

        // Intermediary Type
        $intermediaryTypes = ['Agent', 'Prescripteur'];
        foreach ($intermediaryTypes as $type) {
            $intermediaryType = new IntermediaryType();
            $intermediaryType->setName($type);
            $manager->persist($intermediaryType);
        }
        $manager->flush();

        // Event Nomenclature
        $eventNames = [
            [
                'name' => 'Creation du contact',
                'is_automatic' => true,
                'automatic_followup_delay' => 0,
            ],
            [
                'name' => 'Nouveau Commercial',
                'is_automatic' => true,
                'automatic_followup_delay' => 0,
            ],
            [
                'name' => 'Point Projet',
                'is_automatic' => false,
                'automatic_followup_delay' => 0,
            ],
            [
                'name' => 'Passage Galerie',
                'is_automatic' => false,
                'automatic_followup_delay' => 0,
            ],
            [
                'name' => 'Contact Mail (entrant)',
                'is_automatic' => false,
                'automatic_followup_delay' => 0,
            ],
            [
                'name' => 'Contact Mail (sortant)',
                'is_automatic' => false,
                'automatic_followup_delay' => 0,
            ],
            [
                'name' => 'Appel (entrant)',
                'is_automatic' => false,
                'automatic_followup_delay' => 0,
            ],
            [
                'name' => 'Appel (sortant)',
                'is_automatic' => false,
                'automatic_followup_delay' => 0,
            ],
            [
                'name' => 'RDV chez le client',
                'is_automatic' => false,
                'automatic_followup_delay' => 0,
            ],
            [
                'name' => 'Nouveau Devis',
                'is_automatic' => true,
                'automatic_followup_delay' => 30,
            ],
            [
                'name' => 'Nouvelle DI Projet',
                'is_automatic' => true,
                'automatic_followup_delay' => 30,
            ],
            [
                'name' => 'Validation du Devis - Commande',
                'is_automatic' => true,
                'automatic_followup_delay' => 0,
            ],
            [
                'name' => 'Demande de Prix (site)',
                'is_automatic' => false,
                'automatic_followup_delay' => 0,
            ],
            [
                'name' => 'Relance',
                'is_automatic' => false,
                'automatic_followup_delay' => 15,
            ],
            [
                'name' => 'Envoi (échantillon - Tapis - Documentation)',
                'is_automatic' => false,
                'automatic_followup_delay' => 0,
            ],
            [
                'name' => 'Nouvelle Commande',
                'is_automatic' => true,
                'automatic_followup_delay' => 0,
            ],
            [
                'name' => 'Nouveau Devis Client',
                'is_automatic' => true,
                'automatic_followup_delay' => 15,
            ],
        ];

        foreach ($eventNames as $eventName) {
            $eventNomenclature = new EventNomenclature();
            $eventNomenclature->setSubject($eventName['name']);
            $eventNomenclature->setIsAutomatic($eventName['is_automatic']);
            $eventNomenclature->setAutomaticFollowupDelay($eventName['automatic_followup_delay']);
            $manager->persist($eventNomenclature);
        }
        $manager->flush();
        // 50 agent
        for ($i = 0; $i < 50; ++$i) {
            $customer = new Customer();
            $discountRule = $this->discountRuleRepository->selectRandomDiscountRule();
            $language = $this->languageRepository->selectRandomLanguage();
            $customer->setDiscountRule($discountRule);
            $customerGroup = $this->customerGroupRepository->selectRandomCustomerGroup();
            $customer->setActive((bool) random_int(0, 1));
            $customer->setCustomerGroup($customerGroup);
            $customer->setWebsite($faker->url);
            $customer->setCode(mt_rand(1000000000, 9999999999));
            $customer->setTvaCe(20);
            $customer->setSocialReason($faker->company);
            $customer->setMailingLanguage($language);
            $customer->setIntermediary(true);
            $intermediaryInformationSheet = new IntermediaryInformationSheet();
            $intermediaryInformationSheet->setSaleCondition($faker->realTextBetween);
            $intermediaryInformationSheet->setComission($faker->randomFloat(null, 0, 100));
            $intermediaryType = $this->intermediaryTypeRepository->findOneByName('Agent');
            $intermediaryInformationSheet->setIntermediaryType($intermediaryType);
            $bankInformationSheet = new BankInformationSheet();
            $bankInformationSheet->setBankName($faker->realTextBetween);
            $bankInformationSheet->setIban($faker->realTextBetween);
            $bankInformationSheet->setSwiftCode($faker->password);
            $manager->persist($bankInformationSheet);
            if (!empty($bankInformationSheet)) {
                $intermediaryInformationSheet->setBankInformationSheet($bankInformationSheet);
            }
            $manager->persist($intermediaryInformationSheet);
            $customer->setIntermediaryInformationSheet($intermediaryInformationSheet);
            $manager->persist($customer);
            for ($j = 0; $j < 2; ++$j) {
                $firstName = $faker->firstName;
                $lastName = $faker->lastName;
                $email = $faker->email;
                $e164PhoneNumber = $faker->e164PhoneNumber;
                $user = new User();
                $user->setEmail($email);
                $user->setPassword($this->hasher->hashPassword($user, '123@123@1234')); // Replace with your password encoder service
                $user->setRoles(['ROLE_USER']);
                $user->setFirstname($firstName);
                $user->setLastname($lastName);
                $user->setProfile($client);

                $gender = $this->genderRepository->selectRandomGender();

                if ($gender instanceof Gender) {
                    $user->setGender($gender);
                }
                $manager->persist($user);
                $contactInformation = new ContactInformationSheet();
                $contactInformation->setGender($gender);
                $contactInformation->setFirstname($firstName);
                $contactInformation->setLastname($lastName);
                $contactInformation->setEmail($email);
                $contactInformation->setPhone(substr($e164PhoneNumber, 0, 10));
                $contactInformation->setMobilePhone(substr($e164PhoneNumber, 0, 10));
                $contactInformation->setFax(substr($e164PhoneNumber, 0, 10));
                $contactInformation->setUser($user);
                $manager->persist($contactInformation);

                $contact = new Contact();
                $contact->setContactInformationSheet($contactInformation);
                $contact->setMailing((bool) random_int(0, 1));
                $contact->setMailingWithCaligraphie((bool) random_int(0, 1));
                $manager->persist($contact);
                $customer->addContact($contact);

                $address = new Address();
                $address->setFullName($firstName . ' ' . $lastName);
                $address->setAddress1($faker->streetAddress);
                $addressType = $this->addressTypeRepository->selectRandomAddressType();
                $address->setAddressType($addressType);
                $country = $this->countryRepository->findOneBy(['name' => 'France']);
                $address->setCountry($country);
                $address->setCity($faker->city);
                $address->setIsLValide((bool) random_int(0, 1));
                $address->setIsFValide((bool) random_int(0, 1));
                $address->setIsWrong((bool) random_int(0, 1));
                $address->setZipCode($faker->postcode);
                $manager->persist($address);
                $customer->addAddress($address);
                $nomenclature = $this->eventNomenclatureRepository->findBySubject('Creation du contact');
                $event = new Event();
                $event->setNomenclature($nomenclature);
                $event->setCustomer($customer);
                $event->setEventDate(new DateTimeImmutable());
                $event->setCommentaire($firstName . ' ' . $lastName);
                $manager->persist($event);
            }
            for ($t = 0; $t < 3; ++$t) {
                $commercial = $this->userRepository->selectRandomCommercial();
                $contactCommercialHistory = new ContactCommercialHistory();
                $contactCommercialHistory->setCommercial($commercial);
                $contactCommercialHistory->setCustomer($customer);
                $from = new DateTime('2024-' . ($t + 1) . '-01');
                $to = new DateTime('2024-' . ($t + 2) . '-01');
                $contactCommercialHistory->setFromDate($from);
                $contactCommercialHistory->setToDate($to);
                if (2 === $t) {
                    $contactCommercialHistory->setToDate(null);
                    $status = $this->attributionStatusRepository->findOneByName('Pending');
                } else {
                    $contactCommercialHistory->setToDate($to);
                    $status = $this->attributionStatusRepository->findOneByName('Accepted');
                }

                $contactCommercialHistory->setStatus($status);
                $manager->persist($contactCommercialHistory);

                $nomenclature = $this->eventNomenclatureRepository->findBySubject('Nouveau Commercial');

                $event = new Event();
                $event->setNomenclature($nomenclature);
                $event->setCustomer($customer);
                $event->setEventDate(new DateTimeImmutable());
                $event->setCommentaire($commercial->getFirstname() . ' ' . $commercial->getLastname());
                $manager->persist($event);
            }
            $manager->persist($customer);
        }
        $manager->flush();
        // 50 Prescripteur
        for ($i = 0; $i < 50; ++$i) {
            $customer = new Customer();
            $discountRule = $this->discountRuleRepository->selectRandomDiscountRule();
            $language = $this->languageRepository->selectRandomLanguage();
            $customer->setDiscountRule($discountRule);
            $customerGroup = $this->customerGroupRepository->selectRandomCustomerGroup();
            $customer->setActive((bool) random_int(0, 1));
            $customer->setCustomerGroup($customerGroup);
            $customer->setWebsite($faker->url);
            $customer->setCode(mt_rand(1000000000, 9999999999));
            $customer->setTvaCe(20);
            $customer->setSocialReason($faker->company);
            $customer->setMailingLanguage($language);
            $customer->setIntermediary(true);
            $intermediaryInformationSheet = new IntermediaryInformationSheet();
            $intermediaryInformationSheet->setSaleCondition($faker->realTextBetween);
            $intermediaryInformationSheet->setComission($faker->randomFloat(null, 0, 100));
            $intermediaryType = $this->intermediaryTypeRepository->findOneByName('Prescripteur');
            $intermediaryInformationSheet->setIntermediaryType($intermediaryType);
            $bankInformationSheet = new BankInformationSheet();
            $bankInformationSheet->setBankName($faker->realTextBetween);
            $bankInformationSheet->setIban($faker->realTextBetween);
            $bankInformationSheet->setSwiftCode($faker->password);
            $manager->persist($bankInformationSheet);
            if (!empty($bankInformationSheet)) {
                $intermediaryInformationSheet->setBankInformationSheet($bankInformationSheet);
            }
            $manager->persist($intermediaryInformationSheet);
            $customer->setIntermediaryInformationSheet($intermediaryInformationSheet);
            $manager->persist($customer);
            for ($j = 0; $j < 2; ++$j) {
                $firstName = $faker->firstName;
                $lastName = $faker->lastName;
                $email = $faker->email;
                $e164PhoneNumber = $faker->e164PhoneNumber;
                $user = new User();
                $user->setEmail($email);
                $user->setPassword($this->hasher->hashPassword($user, '123@123@1234')); // Replace with your password encoder service
                $user->setRoles(['ROLE_USER']);
                $user->setFirstname($firstName);
                $user->setLastname($lastName);
                $user->setProfile($client);

                $gender = $this->genderRepository->selectRandomGender();

                if ($gender instanceof Gender) {
                    $user->setGender($gender);
                }
                $manager->persist($user);
                $contactInformation = new ContactInformationSheet();
                $contactInformation->setGender($gender);
                $contactInformation->setFirstname($firstName);
                $contactInformation->setLastname($lastName);
                $contactInformation->setEmail($email);
                $contactInformation->setPhone(substr($e164PhoneNumber, 0, 10));
                $contactInformation->setMobilePhone(substr($e164PhoneNumber, 0, 10));
                $contactInformation->setFax(substr($e164PhoneNumber, 0, 10));
                $contactInformation->setUser($user);
                $manager->persist($contactInformation);

                $contact = new Contact();
                $contact->setContactInformationSheet($contactInformation);
                $contact->setMailing((bool) random_int(0, 1));
                $contact->setMailingWithCaligraphie((bool) random_int(0, 1));
                $manager->persist($contact);
                $customer->addContact($contact);

                $address = new Address();
                $address->setFullName($firstName . ' ' . $lastName);
                $address->setAddress1($faker->streetAddress);
                $addressType = $this->addressTypeRepository->selectRandomAddressType();
                $address->setAddressType($addressType);
                $country = $this->countryRepository->findOneBy(['name' => 'France']);
                $address->setCountry($country);
                $address->setCity($faker->city);
                $address->setIsLValide((bool) random_int(0, 1));
                $address->setIsFValide((bool) random_int(0, 1));
                $address->setIsWrong((bool) random_int(0, 1));
                $address->setZipCode($faker->postcode);
                $manager->persist($address);
                $customer->addAddress($address);
                $nomenclature = $this->eventNomenclatureRepository->findBySubject('Creation du contact');
                $event = new Event();
                $event->setNomenclature($nomenclature);
                $event->setCustomer($customer);
                $event->setEventDate(new DateTimeImmutable());
                $event->setCommentaire($firstName . ' ' . $lastName);
                $manager->persist($event);
            }
            for ($t = 0; $t < 3; ++$t) {
                $commercial = $this->userRepository->selectRandomCommercial();
                $contactCommercialHistory = new ContactCommercialHistory();
                $contactCommercialHistory->setCommercial($commercial);
                $contactCommercialHistory->setCustomer($customer);
                $from = new DateTime('2024-' . ($t + 1) . '-01');
                $to = new DateTime('2024-' . ($t + 2) . '-01');
                $contactCommercialHistory->setFromDate($from);

                if (2 === $t) {
                    $contactCommercialHistory->setToDate(null);
                    $status = $this->attributionStatusRepository->findOneByName('Pending');
                } else {
                    $contactCommercialHistory->setToDate($to);
                    $status = $this->attributionStatusRepository->findOneByName('Accepted');
                }

                $contactCommercialHistory->setStatus($status);
                $manager->persist($contactCommercialHistory);

                $nomenclature = $this->eventNomenclatureRepository->findBySubject('Nouveau Commercial');

                $event = new Event();
                $event->setNomenclature($nomenclature);
                $event->setCustomer($customer);
                $event->setEventDate(new DateTimeImmutable());
                $event->setCommentaire($commercial->getFirstname() . ' ' . $commercial->getLastname());
                $manager->persist($event);
            }
            $manager->persist($customer);
        }
        $manager->flush();
        // 100 B2B customer
        for ($i = 0; $i < 100; ++$i) {
            $customer = new Customer();
            $discountRule = $this->discountRuleRepository->selectRandomDiscountRule();
            $language = $this->languageRepository->selectRandomLanguage();
            $customer->setDiscountRule($discountRule);
            $customerGroup = $this->customerGroupRepository->selectRandomCustomerGroup();
            $customer->setActive((bool) random_int(0, 1));
            $customer->setCustomerGroup($customerGroup);
            $customer->setWebsite($faker->url);
            $customer->setCode(mt_rand(1000000000, 9999999999));
            $customer->setTvaCe(20);
            $customer->setSocialReason($faker->company);
            $customer->setMailingLanguage($language);
            $customer->setIntermediary(false);
            for ($j = 0; $j < 2; ++$j) {
                $firstName = $faker->firstName;
                $lastName = $faker->lastName;
                $email = $faker->email;
                $e164PhoneNumber = $faker->e164PhoneNumber;
                $user = new User();
                $user->setEmail($email);
                $user->setPassword($this->hasher->hashPassword($user, '123@123@1234')); // Replace with your password encoder service
                $user->setRoles(['ROLE_USER']);
                $user->setFirstname($firstName);
                $user->setLastname($lastName);
                $user->setProfile($client);

                $gender = $this->genderRepository->selectRandomGender();

                if ($gender instanceof Gender) {
                    $user->setGender($gender);
                }
                $manager->persist($user);
                $contactInformation = new ContactInformationSheet();
                $contactInformation->setGender($gender);
                $contactInformation->setFirstname($firstName);
                $contactInformation->setLastname($lastName);
                $contactInformation->setEmail($email);
                $contactInformation->setPhone(substr($e164PhoneNumber, 0, 10));
                $contactInformation->setMobilePhone(substr($e164PhoneNumber, 0, 10));
                $contactInformation->setFax(substr($e164PhoneNumber, 0, 10));
                $contactInformation->setUser($user);
                $manager->persist($contactInformation);

                $contact = new Contact();
                $contact->setContactInformationSheet($contactInformation);
                $contact->setMailing((bool) random_int(0, 1));
                $contact->setMailingWithCaligraphie((bool) random_int(0, 1));
                $manager->persist($contact);
                $customer->addContact($contact);

                $address = new Address();
                $address->setFullName($firstName . ' ' . $lastName);
                $address->setAddress1($faker->streetAddress);
                $addressType = $this->addressTypeRepository->selectRandomAddressType();
                $address->setAddressType($addressType);
                $country = $this->countryRepository->findOneBy(['name' => 'France']);
                $address->setCountry($country);
                $address->setCity($faker->city);
                $address->setIsLValide((bool) random_int(0, 1));
                $address->setIsFValide((bool) random_int(0, 1));
                $address->setIsWrong((bool) random_int(0, 1));
                $address->setZipCode($faker->postcode);
                $manager->persist($address);
                $customer->addAddress($address);
                $nomenclature = $this->eventNomenclatureRepository->findBySubject('Creation du contact');
                $event = new Event();
                $event->setNomenclature($nomenclature);
                $event->setCustomer($customer);
                $event->setEventDate(new DateTimeImmutable());
                $event->setCommentaire($firstName . ' ' . $lastName);
                $manager->persist($event);
            }
            for ($t = 0; $t < 3; ++$t) {
                $commercial = $this->userRepository->selectRandomCommercial();
                $contactCommercialHistory = new ContactCommercialHistory();
                $contactCommercialHistory->setCommercial($commercial);
                $contactCommercialHistory->setCustomer($customer);
                $from = new DateTime('2024-' . ($t + 1) . '-01');
                $to = new DateTime('2024-' . ($t + 2) . '-01');
                $contactCommercialHistory->setFromDate($from);

                if (2 === $t) {
                    $contactCommercialHistory->setToDate(null);
                    $status = $this->attributionStatusRepository->findOneByName('Pending');
                } else {
                    $contactCommercialHistory->setToDate($to);
                    $status = $this->attributionStatusRepository->findOneByName('Accepted');
                }

                $contactCommercialHistory->setStatus($status);
                $manager->persist($contactCommercialHistory);

                $nomenclature = $this->eventNomenclatureRepository->findBySubject('Nouveau Commercial');

                $event = new Event();
                $event->setNomenclature($nomenclature);
                $event->setCustomer($customer);
                $event->setEventDate(new DateTimeImmutable());
                $event->setCommentaire($commercial->getFirstname() . ' ' . $commercial->getLastname());
                $manager->persist($event);

                $agent = $this->customerRepository->selectRandomAgent();
                $customerIntermediaryHistory = new CustomerIntermediaryHistory();
                $customerIntermediaryHistory->setCustomer($customer);
                $customerIntermediaryHistory->setIntermediary($agent);
                $customerIntermediaryHistory->setDateFrom($from);
                $customerIntermediaryHistory->setDateTo($to);
                $manager->persist($customerIntermediaryHistory);

                $prescripteur = $this->customerRepository->selectRandomPrescripteur();
                $customerIntermediaryHistory = new CustomerIntermediaryHistory();
                $customerIntermediaryHistory->setCustomer($customer);
                $customerIntermediaryHistory->setIntermediary($prescripteur);
                $customerIntermediaryHistory->setDateFrom($from);
                $customerIntermediaryHistory->setDateTo($to);
                $manager->persist($customerIntermediaryHistory);
            }
            $manager->persist($customer);
        }
        // 100 Particular customer
        for ($i = 0; $i < 100; ++$i) {
            $customer = new Customer();
            $discountRule = $this->discountRuleRepository->selectRandomDiscountRule();
            $language = $this->languageRepository->selectRandomLanguage();
            $customer->setDiscountRule($discountRule);
            $customerGroup = $this->customerGroupRepository->findOneBy(['name' => 'Particulier (Client)']);
            $customer->setActive((bool) random_int(0, 1));
            $customer->setCustomerGroup($customerGroup);
            $customer->setCode(mt_rand(1000000000, 9999999999));
            $customer->setMailingLanguage($language);
            $customer->setIntermediary(false);
            $firstName = $faker->firstName;
            $lastName = $faker->lastName;
            $email = $faker->email;
            $e164PhoneNumber = $faker->e164PhoneNumber;
            $user = new User();
            $user->setEmail($email);
            $user->setPassword($this->hasher->hashPassword($user, '123@123@1234')); // Replace with your password encoder service
            $user->setRoles(['ROLE_USER']);
            $user->setFirstname($firstName);
            $user->setLastname($lastName);
            $user->setProfile($client);

            $gender = $this->genderRepository->selectRandomGender();

            if ($gender instanceof Gender) {
                $user->setGender($gender);
            }
            $manager->persist($user);
            $contactInformation = new ContactInformationSheet();
            $contactInformation->setGender($gender);
            $contactInformation->setFirstname($firstName);
            $contactInformation->setLastname($lastName);
            $contactInformation->setEmail($email);
            $contactInformation->setPhone(substr($e164PhoneNumber, 0, 10));
            $contactInformation->setMobilePhone(substr($e164PhoneNumber, 0, 10));
            $contactInformation->setFax(substr($e164PhoneNumber, 0, 10));
            $contactInformation->setUser($user);
            $manager->persist($contactInformation);
            $customer->setContactInformationSheet($contactInformation);
            $manager->persist($customer);
            $contact = new Contact();
            $contact->setContactInformationSheet($contactInformation);
            $contact->setMailing((bool) random_int(0, 1));
            $contact->setMailingWithCaligraphie((bool) random_int(0, 1));
            $manager->persist($contact);
            $customer->addContact($contact);

            $address = new Address();
            $address->setFullName($firstName . ' ' . $lastName);
            $address->setAddress1($faker->streetAddress);
            $addressType = $this->addressTypeRepository->selectRandomAddressType();
            $address->setAddressType($addressType);
            $country = $this->countryRepository->findOneBy(['name' => 'France']);
            $address->setCountry($country);
            $address->setCity($faker->city);
            $address->setIsLValide((bool) random_int(0, 1));
            $address->setIsFValide((bool) random_int(0, 1));
            $address->setIsWrong((bool) random_int(0, 1));
            $address->setZipCode($faker->postcode);
            $manager->persist($address);
            $customer->addAddress($address);
            $nomenclature = $this->eventNomenclatureRepository->findBySubject('Creation du contact');
            $event = new Event();
            $event->setNomenclature($nomenclature);
            $event->setCustomer($customer);
            $event->setEventDate(new DateTimeImmutable());
            $event->setCommentaire($firstName . ' ' . $lastName);
            $manager->persist($event);
            for ($j = 0; $j < 1; ++$j) {
                $firstName = $faker->firstName;
                $lastName = $faker->lastName;
                $email = $faker->email;
                $e164PhoneNumber = $faker->e164PhoneNumber;
                $user = new User();
                $user->setEmail($email);
                $user->setPassword($this->hasher->hashPassword($user, '123@123@1234')); // Replace with your password encoder service
                $user->setRoles(['ROLE_USER']);
                $user->setFirstname($firstName);
                $user->setLastname($lastName);
                $user->setProfile($client);

                $gender = $this->genderRepository->selectRandomGender();

                if ($gender instanceof Gender) {
                    $user->setGender($gender);
                }
                $manager->persist($user);
                $contactInformation = new ContactInformationSheet();
                $contactInformation->setGender($gender);
                $contactInformation->setFirstname($firstName);
                $contactInformation->setLastname($lastName);
                $contactInformation->setEmail($email);
                $contactInformation->setPhone(substr($e164PhoneNumber, 0, 10));
                $contactInformation->setMobilePhone(substr($e164PhoneNumber, 0, 10));
                $contactInformation->setFax(substr($e164PhoneNumber, 0, 10));
                $contactInformation->setUser($user);
                $manager->persist($contactInformation);

                $contact = new Contact();
                $contact->setContactInformationSheet($contactInformation);
                $contact->setMailing((bool) random_int(0, 1));
                $contact->setMailingWithCaligraphie((bool) random_int(0, 1));
                $manager->persist($contact);
                $customer->addContact($contact);

                $address = new Address();
                $address->setFullName($firstName . ' ' . $lastName);
                $address->setAddress1($faker->streetAddress);
                $addressType = $this->addressTypeRepository->selectRandomAddressType();
                $address->setAddressType($addressType);
                $country = $this->countryRepository->findOneBy(['name' => 'France']);
                $address->setCountry($country);
                $address->setCity($faker->city);
                $address->setIsLValide((bool) random_int(0, 1));
                $address->setIsFValide((bool) random_int(0, 1));
                $address->setIsWrong((bool) random_int(0, 1));
                $address->setZipCode($faker->postcode);
                $manager->persist($address);
                $customer->addAddress($address);
                $nomenclature = $this->eventNomenclatureRepository->findBySubject('Creation du contact');
                $event = new Event();
                $event->setNomenclature($nomenclature);
                $event->setCustomer($customer);
                $event->setEventDate(new DateTimeImmutable());
                $event->setCommentaire($firstName . ' ' . $lastName);
                $manager->persist($event);
            }
            for ($t = 0; $t < 3; ++$t) {
                $commercial = $this->userRepository->selectRandomCommercial();
                $contactCommercialHistory = new ContactCommercialHistory();
                $contactCommercialHistory->setCommercial($commercial);
                $contactCommercialHistory->setCustomer($customer);
                $from = new DateTime('2024-' . ($t + 1) . '-01');
                $to = new DateTime('2024-' . ($t + 2) . '-01');
                $contactCommercialHistory->setFromDate($from);

                if (2 === $t) {
                    $contactCommercialHistory->setToDate(null);
                    $status = $this->attributionStatusRepository->findOneByName('Pending');
                } else {
                    $contactCommercialHistory->setToDate($to);
                    $status = $this->attributionStatusRepository->findOneByName('Accepted');
                }

                $contactCommercialHistory->setStatus($status);
                $manager->persist($contactCommercialHistory);

                $nomenclature = $this->eventNomenclatureRepository->findBySubject('Nouveau Commercial');

                $event = new Event();
                $event->setNomenclature($nomenclature);
                $event->setCustomer($customer);
                $event->setEventDate(new DateTimeImmutable());
                $event->setCommentaire($commercial->getFirstname() . ' ' . $commercial->getLastname());
                $manager->persist($event);

                $agent = $this->customerRepository->selectRandomAgent();
                $customerIntermediaryHistory = new CustomerIntermediaryHistory();
                $customerIntermediaryHistory->setCustomer($customer);
                $customerIntermediaryHistory->setIntermediary($agent);
                $customerIntermediaryHistory->setDateFrom($from);
                $customerIntermediaryHistory->setDateTo($to);
                $manager->persist($customerIntermediaryHistory);

                $prescripteur = $this->customerRepository->selectRandomPrescripteur();
                $customerIntermediaryHistory = new CustomerIntermediaryHistory();
                $customerIntermediaryHistory->setCustomer($customer);
                $customerIntermediaryHistory->setIntermediary($prescripteur);
                $customerIntermediaryHistory->setDateFrom($from);
                $customerIntermediaryHistory->setDateTo($to);
                $manager->persist($customerIntermediaryHistory);
            }
            $manager->persist($customer);
        }
        $manager->flush();
        $output->writeln('Data seeding complete.');
    }
}
