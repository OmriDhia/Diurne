<?php

// src/Contremarque/Command/CreateDiStatusCommand.php

namespace App\Contremarque\Command;

use DateTimeImmutable;
use DateInterval;
use RuntimeException;
use InvalidArgumentException;
use App\Contact\Repository\CustomerRepository;
use App\Contremarque\Entity\Attachment;
use App\Contremarque\Entity\CarpetComposition;
use App\Contremarque\Entity\CarpetDesignOrder;
use App\Contremarque\Entity\CarpetDesignOrderAttachment;
use App\Contremarque\Entity\CarpetDimension;
use App\Contremarque\Entity\CarpetDimensionValue;
use App\Contremarque\Entity\CarpetMaterial;
use App\Contremarque\Entity\CarpetReference;
use App\Contremarque\Entity\CarpetSpecification;
use App\Contremarque\Entity\Contremarque;
use App\Contremarque\Entity\ContremarqueContact;
use App\Contremarque\Entity\DesignerAssignment;
use App\Contremarque\Entity\DesignerComposition;
use App\Contremarque\Entity\DiAttachment;
use App\Contremarque\Entity\Image;
use App\Contremarque\Entity\ImageAttachment;
use App\Contremarque\Entity\Layer;
use App\Contremarque\Entity\LayerDetail;
use App\Contremarque\Entity\Location;
use App\Contremarque\Entity\ProjectDi;
use App\Contremarque\Entity\Thread;
use App\Contremarque\Repository\AttachmentRepository;
use App\Contremarque\Repository\CarpetDesignOrderAttachmentRepository;
use App\Contremarque\Repository\CarpetDesignOrderRepository;
use App\Contremarque\Repository\CarpetReferenceRepository;
use App\Contremarque\Repository\CarpetStatusRepository;
use App\Contremarque\Repository\CarpetTypeRepository;
use App\Contremarque\Repository\ContremarqueContactRepository;
use App\Contremarque\Repository\ContremarqueRepository;
use App\Contremarque\Repository\DiAttachmentRepository;
use App\Contremarque\Repository\ImageAttachmentRepository;
use App\Contremarque\Repository\ImageRepository;
use App\Contremarque\Repository\LocationRepository;
use App\Contremarque\Repository\MesurementRepository;
use App\Contremarque\Repository\ProjectDiRepository;
use App\Contremarque\Repository\ThreadRepository;
use App\Contremarque\Repository\UnitOfMeasurementRepository;
use App\Event\Entity\Event;
use App\Event\Repository\EventNomenclatureRepository;
use App\Event\Repository\EventRepository;
use App\Setting\Entity\AttachmentType;
use App\Setting\Entity\ImageType;
use App\Setting\Repository\AttachmentTypeRepository;
use App\Setting\Repository\CarpetCollectionRepository;
use App\Setting\Repository\ColorRepository;
use App\Setting\Repository\DiscountRuleRepository;
use App\Setting\Repository\DominantColorRepository;
use App\Setting\Repository\ImageTypeRepository;
use App\Setting\Repository\MaterialRepository;
use App\Setting\Repository\ModelRepository;
use App\Setting\Repository\QualityRepository;
use App\Setting\Repository\SpecialShapeRepository;
use App\User\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'app:create-contremarque-data-fixtures',
    description: 'Creates contremarque fixtures'
)]
class CreateContremarqueFixtureDataCommand extends Command
{
    protected static $defaultName = 'app:create-contremarque-data-fixtures';

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ContremarqueRepository $contremarqueRepository,
        private readonly CustomerRepository $customerRepository,
        private readonly ContremarqueContactRepository $contremarqueContactRepository,
        private readonly CarpetTypeRepository $carpetTypeRepository,
        private readonly LocationRepository $locationRepository,
        private readonly ProjectDiRepository $projectDiRepository,
        private readonly UnitOfMeasurementRepository $unitOfMeasurementRepository,
        private readonly CarpetStatusRepository $carpetStatusRepository,
        private readonly CarpetDesignOrderRepository $carpetDesignOrderRepository,
        private readonly CarpetCollectionRepository $carpetCollectionRepository,
        private readonly ModelRepository $modelRepository,
        private readonly QualityRepository $qualityRepository,
        private readonly SpecialShapeRepository $specialShapeRepository,
        private readonly MesurementRepository $mesurementRepository,
        private readonly UserRepository $userRepository,
        private readonly EventRepository $eventRepository,
        private readonly EventNomenclatureRepository $eventNomenclatureRepository,
        private readonly CarpetReferenceRepository $carpetReferenceRepository,
        private readonly DiscountRuleRepository $discountRuleRepository,
        private readonly MaterialRepository $materialRepository,
        private readonly ColorRepository $colorRepository,
        private readonly DominantColorRepository $dominantColorRepository,
        private readonly ThreadRepository $threadRepository,
        private readonly ImageRepository $imageRepository,
        private readonly ParameterBagInterface $params,
        private readonly DiAttachmentRepository $diAttachmentRepository,
        private readonly CarpetDesignOrderAttachmentRepository $carpetDesignOrderAttachmentRepository,
        private readonly ImageAttachmentRepository $imageAttachmentRepository,
        private readonly ImageTypeRepository $imageTypeRepository,
        private readonly AttachmentTypeRepository $attachmentTypeRepository,
        private readonly AttachmentRepository $attachmentRepository
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this->addOption(
            'reset',
            null,
            InputOption::VALUE_NONE,
            'If set, the existing data will be deleted before creating new fixture data'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '-1');

        //        if ($input->getOption('reset')) {
        $output->writeln('Resetting existing data...');
        $contremarques = $this->contremarqueRepository->findAll();

        if (count($contremarques)) {
            foreach ($contremarques as $contremarque) {
                $events = $contremarque->getEvents();
                if (!empty($events)) {
                    foreach ($events as $event) {
                        $this->entityManager->remove($event);
                    }
                }
                $projectDis = $contremarque->getProjectDis();
                if ($projectDis) {
                    foreach ($projectDis as $projectDi) {
                        $carpetDesignOrders = $projectDi->getCarpetDesignOrders();
                        if ($carpetDesignOrders) {
                            foreach ($carpetDesignOrders as $carpetDesignOrder) {
                                $designers = $carpetDesignOrder->getDesigners();
                                if ($designers) {
                                    foreach ($designers as $designer) {
                                        $this->entityManager->remove($designer);
                                    }
                                }
                                $carpetDesignOrderAttachments = $this->carpetDesignOrderAttachmentRepository->findBy(['carpetDesignOrder' => $carpetDesignOrder]);
                                if ($carpetDesignOrderAttachments) {
                                    foreach ($carpetDesignOrderAttachments as $carpetDesignOrderAttachment) {
                                        $attachment = $carpetDesignOrderAttachment->getAttachment();
                                        $this->entityManager->remove($attachment);
                                        $this->entityManager->remove($carpetDesignOrderAttachment);
                                    }
                                }
                                $images = $carpetDesignOrder->getImages();
                                if ($images) {
                                    foreach ($images as $image) {
                                        $imageAttachments = $this->imageAttachmentRepository->findBy(['image' => $image]);
                                        if (!empty($imageAttachments)) {
                                            foreach ($imageAttachments as $imageAttachment) {
                                                $attachment = $imageAttachment->getAttachment();
                                                $this->entityManager->remove($attachment);
                                                $this->entityManager->remove($image);
                                            }
                                        }
                                    }
                                }
                                $carpetSpecifications = $carpetDesignOrder->getCarpetSpecification();
                                if ($carpetSpecifications) {
                                    $carpetDimensions = $carpetSpecifications->getCarpetDimensions();
                                    if (!empty($carpetDimensions)) {
                                        foreach ($carpetDimensions as $carpetDimension) {
                                            $this->entityManager->remove($carpetDimension);
                                        }
                                    }
                                    $carpetMaterials = $carpetSpecifications->getMaterials();
                                    if (!empty($carpetMaterials)) {
                                        foreach ($carpetMaterials as $carpetMaterial) {
                                            $this->entityManager->remove($carpetMaterial);
                                        }
                                    }
                                    $CarpetDesignerCompositions = $carpetSpecifications->getDesignerCompositions();
                                    if (!empty($CarpetDesignerCompositions)) {
                                        foreach ($CarpetDesignerCompositions as $CarpetDesignerComposition) {
                                            $this->entityManager->remove($CarpetDesignerComposition);
                                        }
                                    }
                                    $carpetComposition = $carpetSpecifications->getCarpetComposition();
                                    if (!empty($carpetSpecifications->getCarpetComposition())) {
                                        $layers = $carpetSpecifications->getCarpetComposition()->getLayers();
                                        if (!empty($layers)) {
                                            foreach ($layers as $layer) {
                                                $layerDetails = $layer->getLayerDetails();
                                                if ($layerDetails->count()) {
                                                    foreach ($layerDetails as $layerDetail) {
                                                        $this->entityManager->remove($layerDetail);
                                                    }
                                                }
                                                $this->entityManager->remove($layer);
                                            }
                                        }
                                        $threads = $carpetSpecifications->getCarpetComposition()->getThreads();
                                        if (!empty($threads)) {
                                            foreach ($threads as $thread) {
                                                $this->entityManager->remove($thread);
                                            }
                                        }
                                    }
                                    if (!empty($carpetComposition)) {
                                        $this->entityManager->remove($carpetComposition);
                                    }

                                    $this->entityManager->remove($carpetSpecifications);
                                }

                                $this->entityManager->remove($carpetDesignOrder);
                            }
                        }
                        $diAttachments = $this->diAttachmentRepository->findBy(['di' => $projectDi]);
                        if ($diAttachments) {
                            foreach ($diAttachments as $diAttachment) {
                                $attachment = $diAttachment->getAttachment();
                                $this->entityManager->remove($attachment);
                                $this->entityManager->remove($diAttachment);
                            }
                        }
                        $this->projectDiRepository->remove($projectDi);
                    }
                }

                $locations = $contremarque->getLocations();
                if ($locations) {
                    foreach ($locations as $location) {
                        $this->locationRepository->remove($location);
                    }
                }
                $contacts = $contremarque->getContremarqueContacts();

                if ($contacts) {
                    foreach ($contacts as $contact) {
                        $this->contremarqueContactRepository->remove($contact);
                    }
                }

                $this->contremarqueRepository->remove($contremarque);
            }
            $this->entityManager->flush();
        }
        $attachments = $this->attachmentRepository->findAll();
        if (!empty($attachments)) {
            foreach ($attachments as $attachment) {
                $this->entityManager->remove($attachment);
            }
        }
        $output->writeln('Existing data deleted.');
        $imageTypesData = $this->imageTypeRepository->findAll();
        if ($imageTypesData) {
            foreach ($imageTypesData as $imageType) {
                $this->entityManager->remove($imageType);
            }
        }
        $imageTypes = [
            'Vignette',
            'A3',
            'A4',
            'Légende A4',
            'Légende A3',
            'Insertion Plan',
            'Projet d\'atelier',
            'Détail A4',
            'Détail A3',
        ];
        foreach ($imageTypes as $imageType) {
            $imageTypeObj = new ImageType();
            $imageTypeObj->setName($imageType);
            $imageTypeObj->setDescription($imageType);
            $this->entityManager->persist($imageTypeObj);
            $this->entityManager->flush();
        }
        $output->writeln('Image types created successfully.');
        $attachmentTypesData = $this->attachmentTypeRepository->findAll();
        if ($attachmentTypesData) {
            foreach ($attachmentTypesData as $attachmentType) {
                $this->entityManager->remove($attachmentType);
            }
        }
        $attachmentTypes = [
            'Image',
            'Psd',
            'pdf',
        ];
        foreach ($attachmentTypes as $attachmentType) {
            $attachmentTypeObj = new AttachmentType();
            $attachmentTypeObj->setName($attachmentType);
            $this->entityManager->persist($attachmentTypeObj);
            $this->entityManager->flush();
        }
        $output->writeln('Attachment types created successfully.');

        $process = new Process(['php', 'bin/console', 'app:create-dummy-users', 'Designer']);
        $process->run();

        if (!$process->isSuccessful()) {
            $output->writeln('Failed to create dummy users: ' . $process->getErrorOutput());

            return Command::FAILURE;
        }

        $output->writeln('Dummy users created successfully.');
        //        }

        $faker = Factory::create();

        $pieces = ['salle à manger', 'salon', 'bureau', 'chambre à coucher'];
        $carpetTypes = ['Tapis', 'Echantillon'];

        for ($i = 0; $i < 100; ++$i) {
            $customer = $this->customerRepository->getRandomCustomer();
            if (empty($customer->getDiscountRule())) {
                $discountRule = $this->discountRuleRepository->selectRandomDiscountRule();
                $customer->setDiscountRule($discountRule);
                $this->entityManager->persist($customer);
                $this->entityManager->flush();
            }

            $contremarque = new Contremarque();
            $contremarque->setProjectNumber($this->contremarqueRepository->getNextProjectNumber());
            $contremarque->setDesignation($faker->sentence(3));
            $contremarque->setDestinationLocation($faker->city);
            $contremarque->setTargetDate($faker->dateTimeBetween('-1 year', '+1 year'));
            $contremarque->setCustomer($customer);
            $contremarque->setCustomerDiscount($customer->getDiscountRule());
            $contremarque->setPrescriber($this->customerRepository->selectRandomPrescripteur());
            $contremarque->setCommission($faker->randomFloat(2, 0, 1000));
            $contremarque->setCommissionOnDeposit($faker->boolean);
            $contremarque->setCreatedAt(new DateTimeImmutable());
            $this->entityManager->persist($contremarque);
            $this->entityManager->flush();
            $output->writeln('create contremarque.');
            for ($j = 0; $j < 3; ++$j) {
                $type = $faker->randomElement($carpetTypes);
                $carpetType = $this->carpetTypeRepository->findOneBy(['name' => $type]);
                $location = new Location();
                $location->setDescription($faker->randomElement($pieces));
                $location->setCarpetType($carpetType);
                $location->setQuoteProcessed($faker->boolean);
                $location->setPriceMin($faker->randomFloat(2, 10, 100));
                $location->setPriceMax($faker->randomFloat(2, 100, 1000));
                $location->setCreatedAt(new DateTimeImmutable());
                $location->setUpdatedAt(new DateTimeImmutable());
                $contremarque->addLocation($location);
                $this->entityManager->persist($location);
                $this->entityManager->flush();
                $carpetReference = new CarpetReference();
                $carpetReference->setLocation($location);
                $carpetReference->setContremarque($contremarque);
                $carpetReference->setReference($this->carpetReferenceRepository->getLastReference($contremarque));
                $carpetReference->setSequenceNumber((int) $this->carpetReferenceRepository->getLastSequenceNumber($contremarque));
                $this->carpetReferenceRepository->persist($carpetReference);
                $this->carpetReferenceRepository->flush();
            }
            $output->writeln('Locations created successfully.');
            $this->entityManager->persist($contremarque);
            $this->entityManager->flush();

            $contacts = $customer->getContact();
            foreach ($contacts as $index => $contact) {
                if (!$this->contremarqueContactRepository->exists($contact, $contremarque)) {
                    $association = new ContremarqueContact();
                    $association->setContremarque($contremarque);
                    $association->setContact($contact);
                    if (0 === $index) {
                        $association->setCurrent(true);
                    }
                    $this->entityManager->persist($association);
                    $this->entityManager->flush();
                }
            }
            $output->writeln('Association created successfully.');
            // create projectDi
            for ($d = 0; $d < 3; ++$d) {
                $projectDi = new ProjectDi();
                $demande_number = $this->projectDiRepository->generateProjectNumber();
                $transmitted = true;
                if (2 == $d) {
                    $transmitted = false;
                }
                // Generate random deadline within the next year
                $deadline = $faker->dateTimeBetween('now', '+1 year');

                // Ensure transmitionDate is before the deadline
                $transmitionDate = $faker->dateTimeBetween('now', $deadline);
                $projectDi->setDemandeNumber($demande_number);
                // Arbitrarily set format to either A3 or A4
                $formats = ['A3', 'A4'];
                $format = $formats[array_rand($formats)];
                $projectDi->setFormat($format);
                $projectDi->setDeadline($deadline);
                $projectDi->setTransmittedToStudio($transmitted);
                $projectDi->setTransmitionDate($transmitionDate);
                $unit = $this->unitOfMeasurementRepository->findRandomUnitOfMeasurement();
                $projectDi->setUnit($unit);
                $projectDi->setContremarque($contremarque);
                $projectDi->setCreatedAt(new DateTimeImmutable());

                $this->entityManager->persist($projectDi);
                $this->entityManager->flush();
                $output->writeln('ProjectDi created successfully.');
                for ($ba = 0; $ba < 2; ++$ba) {
                    $noImagePath = $this->params->get('kernel.project_dir') . '/public/images/no-image.jpg';
                    $uploadDirectory = $this->params->get('upload_directory');
                    $filesystem = new Filesystem();
                    if (!$filesystem->exists($uploadDirectory)) {
                        $filesystem->mkdir($uploadDirectory, 0755);
                    }
                    $destinationPath = $uploadDirectory . '/' . $demande_number . '-' . $ba . '.jpg';
                    if ($filesystem->exists($noImagePath)) {
                        // Copy the placeholder image to the upload directory
                        $filesystem->copy($noImagePath, $destinationPath, true);
                    }

                    $attachment = new Attachment();
                    $attachment->setFile($demande_number . '-' . $ba . '.jpg');
                    $attachment->setExtension('jpg');
                    $uploadDirectory = $this->params->get('upload_directory');
                    $attachment->setPath((string) $uploadDirectory);
                    // Get the size of the file in bytes
                    if ($filesystem->exists($destinationPath)) {
                        $fileSize = filesize($destinationPath);  // Get file size in bytes
                        $attachment->setSize($fileSize);
                    }
                    $attachment->setFromDistantServer(false);
                    $this->entityManager->persist($attachment);
                    $diAttachment = new DiAttachment();
                    $diAttachment->setAttachment($attachment);
                    $diAttachment->setDi($projectDi);
                    $this->entityManager->persist($diAttachment);
                    $this->entityManager->flush();
                }

                for ($bb = 0; $bb < 2; ++$bb) {
                    $attachment = new Attachment();
                    $attachment->setFile($demande_number . '-' . $bb . '.psd');
                    $attachment->setExtension('psd');
                    $attachment->setPath('/var/www/Di/' . $demande_number . '/' . $demande_number . '-' . $bb . '.psd');
                    $attachment->setFromDistantServer(true);
                    $this->entityManager->persist($attachment);
                    $diAttachment = new DiAttachment();
                    $diAttachment->setAttachment($attachment);
                    $diAttachment->setDi($projectDi);
                    $this->entityManager->persist($diAttachment);
                    $this->entityManager->flush();
                }
                for ($bc = 0; $bc < 2; ++$bc) {
                    $attachment = new Attachment();
                    $attachment->setFile($demande_number . '-' . $bc . '.pdf');
                    $attachment->setExtension('pdf');
                    $attachment->setPath('/var/www/Di/' . $demande_number . '/' . $demande_number . '-' . $bc . '.pdf');
                    $attachment->setFromDistantServer(true);
                    $this->entityManager->persist($attachment);
                    $diAttachment = new DiAttachment();
                    $diAttachment->setAttachment($attachment);
                    $diAttachment->setDi($projectDi);
                    $this->entityManager->persist($diAttachment);
                    $this->entityManager->flush();
                }
                $output->writeln('diattachment created successfully.');
                $nomenclature = $this->eventNomenclatureRepository->findBySubject('Nouvelle DI Projet');

                $event = new Event();
                $event->setNomenclature($nomenclature);
                $event->setCustomer($contremarque->getCustomer());
                $event->setContremarque($contremarque);
                $randomMonth = random_int(1, 12); // Mois aléatoire entre 1 et 12
                $randomDay = random_int(1, 28);   // Jour aléatoire entre 1 et 28 pour éviter les problèmes avec février

                $eventDate = new DateTimeImmutable("2024-$randomMonth-$randomDay");
                $event->setEventDate($eventDate);
                $reminderDeadline = $eventDate->add(new DateInterval('P30D'));
                $event->setNextReminderDeadline($reminderDeadline);
                $event->setCommentaire('N° de la Demande: ' . $demande_number);
                $this->eventRepository->persist($event);
                $this->eventRepository->flush();
                $output->writeln('event created successfully.');
                for ($e = 0; $e < 3; ++$e) {
                    // create CarpetDesignOrder
                    $location = $this->locationRepository->findRandomLocationByContremarque($contremarque);
                    $status = $this->carpetStatusRepository->findRandomStatus();

                    $carpetDesignOrder = new CarpetDesignOrder();
                    $carpetDesignOrder->setProjectDi($projectDi);
                    $carpetDesignOrder->setLocation($location);
                    $carpetDesignOrder->setStatus($status);

                    // Handle designer assignments (if needed)

                    $this->carpetDesignOrderRepository->persist($carpetDesignOrder);
                    $this->carpetDesignOrderRepository->flush();
                    $output->writeln('carpet design order created successfully.');
                    for ($n = 0; $n < 2; ++$n) {
                        $noImagePath = $this->params->get('kernel.project_dir') . '/public/images/no-image.jpg';
                        $uploadDirectory = $this->params->get('upload_directory');
                        $filesystem = new Filesystem();
                        if (!$filesystem->exists($uploadDirectory)) {
                            $filesystem->mkdir($uploadDirectory, 0755);
                        }
                        $destinationPath = $uploadDirectory . '/' . $carpetDesignOrder->getId() . '-' . $n . '.jpg';
                        if ($filesystem->exists($noImagePath)) {
                            // Copy the placeholder image to the upload directory
                            $filesystem->copy($noImagePath, $destinationPath, true);
                        }

                        $attachment = new Attachment();
                        $attachment->setFile($carpetDesignOrder->getId() . '-' . $n . '.jpg');
                        $attachment->setExtension('jpg');
                        $attachment->setFromDistantServer(false);
                        $uploadDirectory = $this->params->get('upload_directory');
                        $attachment->setPath((string) $uploadDirectory);
                        $this->entityManager->persist($attachment);
                        $carpetDesignOrderAttachment = new CarpetDesignOrderAttachment();
                        $carpetDesignOrderAttachment->setAttachment($attachment);
                        $carpetDesignOrderAttachment->setCarpetDesignOrder($carpetDesignOrder);
                        $this->entityManager->persist($carpetDesignOrderAttachment);
                        $this->entityManager->flush();
                    }
                    for ($bb = 0; $bb < 2; ++$bb) {
                        $attachment = new Attachment();
                        $attachment->setFile($carpetDesignOrder->getId() . '-' . $bb . '.psd');
                        $attachment->setExtension('psd');
                        $attachment->setPath('/var/www/Di/Image/' . $carpetDesignOrder->getId() . '/' . $carpetDesignOrder->getId() . '-' . $bb . '.psd');
                        $attachment->setFromDistantServer(true);
                        $this->entityManager->persist($attachment);
                        $carpetDesignOrderAttachment = new CarpetDesignOrderAttachment();
                        $carpetDesignOrderAttachment->setAttachment($attachment);
                        $carpetDesignOrderAttachment->setCarpetDesignOrder($carpetDesignOrder);
                        $this->entityManager->persist($carpetDesignOrderAttachment);
                        $this->entityManager->flush();
                    }
                    for ($bc = 0; $bc < 2; ++$bc) {
                        $attachment = new Attachment();
                        $attachment->setFile($carpetDesignOrder->getId() . '-' . $bc . '.pdf');
                        $attachment->setExtension('pdf');
                        $attachment->setPath('/var/www/Di/Image/' . $carpetDesignOrder->getId() . '/' . $carpetDesignOrder->getId() . '-' . $bc . '.pdf');
                        $attachment->setFromDistantServer(true);
                        $this->entityManager->persist($attachment);
                        $carpetDesignOrderAttachment = new CarpetDesignOrderAttachment();
                        $carpetDesignOrderAttachment->setAttachment($attachment);
                        $carpetDesignOrderAttachment->setCarpetDesignOrder($carpetDesignOrder);
                        $this->entityManager->persist($carpetDesignOrderAttachment);
                        $this->entityManager->flush();
                    }
                    $output->writeln('carpet design order attachment created successfully.');
                    // create CarpetSpecification
                    // Fetch related entities
                    $collection = $this->carpetCollectionRepository->findRandomCollection();
                    $model = $this->modelRepository->findRandomModelByCollection($collection);
                    $quality = $this->qualityRepository->findRandomQuality();
                    $specialShape = $this->specialShapeRepository->findRandomSpecialShape();
                    $isOverSizes = [true, false];
                    $isOverSize = $isOverSizes[array_rand($isOverSizes)];
                    $hasSpecialShapes = [true, false];
                    $hasSpecialShape = $hasSpecialShapes[array_rand($hasSpecialShapes)];
                    // Create or update CarpetSpecification entity
                    $carpetSpecification = new CarpetSpecification();
                    $carpetSpecification
                        ->setCarpetReference($faker->password)
                        ->setDescription($faker->text)
                        ->setCollection($collection)
                        ->setModel($model)
                        ->setQuality($quality)
                        ->setHasSpecialShape($hasSpecialShape)
                        ->setOversized($isOverSize)
                        ->setSpecialShape($specialShape);

                    $dimensions = [
                        'Largeur' => [
                            'cm' => 230,
                            'ft' => 7,
                            'inch' => 6.55,
                        ],
                        'Longueur' => [
                            'cm' => 90,
                            'ft' => 2,
                            'inch' => 11.43,
                        ],
                    ];

                    // Handle dimensions
                    foreach ($dimensions as $measurementName => $dimensionData) {
                        $dimension = new CarpetDimension();
                        $dimension->setCarpetSpecification($carpetSpecification);
                        $mesurement = $this->mesurementRepository->findOneBy(['name' => $measurementName]);
                        $dimension->setMesurement($mesurement);
                        if (!empty($dimensionData)) {
                            foreach ($dimensionData as $abbreviation => $value) {
                                $carpetDimensionValue = new CarpetDimensionValue();
                                $unit = $this->unitOfMeasurementRepository->findOneBy(['abbreviation' => $abbreviation]);
                                $carpetDimensionValue->setUnit($unit);
                                $carpetDimensionValue->setValue($value);
                                $this->entityManager->persist($carpetDimensionValue);
                                $dimension->addDimensionValue($carpetDimensionValue);
                            }
                        }

                        // Add dimension to CarpetSpecification
                        $carpetSpecification->addCarpetDimension($dimension);
                        $this->entityManager->persist($dimension);
                        $this->entityManager->persist($carpetSpecification);
                    }
                    $output->writeln('carpet specification created successfully.');
                    // create material composition
                    for ($t = 0; $t < 3; ++$t) {
                        $carpetMaterial = new CarpetMaterial();
                        $carpetMaterial->setMaterial($this->materialRepository->findRandomMaterial());
                        $randomRate = mt_rand(0, 10000) / 100;
                        $carpetMaterial->setRate($randomRate);
                        $this->entityManager->persist($carpetMaterial);
                        $carpetSpecification->addMaterial($carpetMaterial);
                    }
                    $output->writeln('carpet materials created successfully.');
                    // create designer material
                    for ($a = 0; $a < 3; ++$a) {
                        $designerComposition = new DesignerComposition();
                        $designerComposition->setMaterial($this->materialRepository->findRandomMaterial());
                        $randomRate = mt_rand(0, 10000) / 100;
                        $designerComposition->setRate($randomRate);
                        $this->entityManager->persist($designerComposition);
                        $carpetSpecification->addDesignerComposition($designerComposition);
                    }
                    $output->writeln('carpet designer materials created successfully.');
                    // create thread composition
                    $carpetComposition = new CarpetComposition();
                    $carpetComposition->setLayerCount(5);
                    $carpetComposition->setThreadCount(3);
                    $carpetComposition->setTrame('to specify later');
                    $this->entityManager->persist($carpetComposition);

                    for ($v = 0; $v < 3; ++$v) {
                        $thread = new Thread();
                        $thread->setThreadNumber($v + 1);
                        $dominantColor = $this->dominantColorRepository->findRandomColor();
                        $thread->setTechColor($dominantColor);
                        $thread->setCarpetComposition($carpetComposition);
                        $this->entityManager->persist($thread);
                        $this->entityManager->flush();
                    }
                    $output->writeln('threads created successfully.');

                    for ($x = 0; $x < 5; ++$x) {
                        $layer = new Layer();
                        $layer->setLayerNumber($x + 1);
                        $layer->setRemarque($faker->text);
                        $layer->setCarpetComposition($carpetComposition);
                        $this->entityManager->persist($layer);

                        for ($z = 0; $z < 3; ++$z) {
                            $layerDetail = new LayerDetail();
                            $thread = $this->threadRepository->findOneBy(['thread_number' => $z + 1, 'carpetComposition' => $carpetComposition]);
                            $layerDetail->setThread($thread);
                            $layerDetail->setLayer($layer);
                            $layerDetail->setColor($this->colorRepository->findRandomColor());
                            $layerDetail->setMaterial($this->materialRepository->findRandomMaterial());
                            $layerDetail->setPercentage(mt_rand(0, 10000) / 100);
                            $this->entityManager->persist($layerDetail);
                        }
                    }
                    $output->writeln('layers created successfully.');
                    $carpetSpecification->setCarpetComposition($carpetComposition);
                    $this->entityManager->persist($carpetSpecification);
                    $carpetDesignOrder->setCarpetSpecification($carpetSpecification);
                    $this->entityManager->persist($carpetDesignOrder);
                    $output->writeln('carpet design order created successfully.');
                    // assign designer
                    for ($j = 0; $j < 3; ++$j) {
                        $designer = $this->userRepository->findAvailableDesigner($carpetDesignOrder);
                        if ($designer) {
                            $designerAssignment = new DesignerAssignment();
                            $designerAssignment->setDesigner($designer);
                            // Generate random deadline within the next year
                            $from = $faker->dateTimeBetween('now', '+1 year');

                            // Ensure transmitionDate is before the deadline
                            $to = $faker->dateTimeBetween($from, '+1 year');
                            $designerAssignment->setDateFrom($from);
                            $designerAssignment->setDateTo($to);
                            if (2 == $j) {
                            }
                            $designerAssignment->setInProgress(false);
                            $designerAssignment->setStopped(false);
                            $designerAssignment->setDone(false);
                            $designerAssignment->setCarpetDesignOrder($carpetDesignOrder);

                            $this->entityManager->persist($designerAssignment);
                            $this->entityManager->flush();
                        }
                    }
                    $output->writeln('designer assignment created successfully.');

                    // create Images
                    for ($h = 0; $h < 4; ++$h) {
                        $reference = $this->imageRepository->getNextImageNumber();

                        // Assuming you have a "NoImage" file at a specific path
                        $noImagePath = $this->params->get('kernel.project_dir') . '/public/images/no-image.jpg';
                        $uploadDirectory = $this->params->get('upload_directory');
                        $filesystem = new Filesystem();
                        if (!$filesystem->exists($uploadDirectory)) {
                            $filesystem->mkdir($uploadDirectory, 0755);
                        }

                        $destinationPath = $uploadDirectory . '/' . $reference . '-' . $h . '.jpg';
                        if ($filesystem->exists($noImagePath)) {
                            // Copy the placeholder image to the upload directory
                            $filesystem->copy($noImagePath, $destinationPath, true);
                        }

                        $attachment = new Attachment();
                        $attachment->setFile($reference . '-' . $h . '.jpg');
                        $attachmentType = $this->attachmentTypeRepository->findOneBy(['name' => 'Image']);
                        $attachment->setAttachmentType($attachmentType);
                        $attachment->setExtension('jpg');
                        $attachment->setFromDistantServer(false);
                        $uploadDirectory = $this->params->get('upload_directory');
                        $attachment->setPath((string) $uploadDirectory);
                        $this->entityManager->persist($attachment);

                        if (0 === $h) {
                            $imageType = $this->imageTypeRepository->findOneBy(['name' => 'Vignette']);
                        } else {
                            $imageType = $this->imageTypeRepository->getRandomImageType();
                        }

                        $image = new Image();
                        $image->setCarpetDesignOrder($carpetDesignOrder);
                        $image->setCommentaire($faker->text);
                        $image->setHasError(false);
                        $image->setImageType($imageType);
                        $image->setValidated(true);
                        $image->setError(null);
                        $image->setImageReference($reference);
                        $image->setValidatedAt(new DateTimeImmutable());
                        $this->entityManager->persist($image);

                        $imageAttachment = new ImageAttachment();
                        $imageAttachment->setAttachment($attachment);
                        $imageAttachment->setImage($image);
                        $this->entityManager->persist($imageAttachment);
                        $this->entityManager->flush();
                        if ('Vignette' === $image->getImageType()->getName()) {
                            $this->resizeImage($attachment->getPath() . '/' . $attachment->getFile(), 90, 90);
                        }
                        $output->writeln('image ' . $h . ' attachment created successfully.');
                    }

                    $this->entityManager->flush();
                }
                $this->entityManager->flush();
            }
            $output->writeln('di created successfully.');
            $this->entityManager->flush();
            $output->writeln('contremarque ' . $i . ' created successfully.');
        }
        $output->writeln('contremarques created successfully.');
        $this->entityManager->flush();

        return Command::SUCCESS;
    }

    private function resizeImage(string $filePath, int $width, int $height): void
    {
        // Get the original image size
        [$originalWidth, $originalHeight] = getimagesize($filePath);
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $filename = pathinfo($filePath, PATHINFO_FILENAME);

        if (!$originalWidth || !$originalHeight) {
            throw new RuntimeException('Unable to get image size');
        }

        // Create a new blank image with desired dimensions
        $resizedImage = imagecreatetruecolor($width, $height);

        // Load the image based on its extension
        switch ($extension) {
            case 'jpeg':
            case 'jpg':
                $originalImage = imagecreatefromjpeg($filePath);
                break;
            case 'png':
                $originalImage = imagecreatefrompng($filePath);
                // Preserve transparency for PNG
                imagealphablending($resizedImage, false);
                imagesavealpha($resizedImage, true);
                break;
            case 'gif':
                $originalImage = imagecreatefromgif($filePath);
                break;
            case 'webp':
                $originalImage = imagecreatefromwebp($filePath);
                break;
            default:
                throw new InvalidArgumentException('Unsupported image format');
        }

        // Resize the image
        imagecopyresampled(
            $resizedImage,
            $originalImage,
            0,
            0,
            0,
            0,
            $width,
            $height,
            $originalWidth,
            $originalHeight
        );

        // Define the /resized directory
        $resizedDirectory = dirname($filePath) . '/resized';

        // Check if the resized directory exists, if not, create it
        if (!is_dir($resizedDirectory)) {
            if (!mkdir($resizedDirectory, 0755, true) && !is_dir($resizedDirectory)) {
                throw new RuntimeException(sprintf('Directory "%s" was not created', $resizedDirectory));
            }
        }

        // Save the resized image to the /resized directory
        $resizedFilePath = $resizedDirectory . '/' . $filename . '_' . $width . 'x' . $height . '.' . $extension;

        switch ($extension) {
            case 'jpeg':
            case 'jpg':
                imagejpeg($resizedImage, $resizedFilePath);
                break;
            case 'png':
                imagepng($resizedImage, $resizedFilePath);
                break;
            case 'gif':
                imagegif($resizedImage, $resizedFilePath);
                break;
            case 'webp':
                imagewebp($resizedImage, $resizedFilePath);
                break;
        }

        // Free up memory
        imagedestroy($originalImage);
        imagedestroy($resizedImage);
    }
}
