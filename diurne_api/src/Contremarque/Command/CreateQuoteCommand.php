<?php

namespace App\Contremarque\Command;

use Exception;
use Throwable;
use App\Common\Bus\Command\CommandBus;
use App\Contact\Repository\AddressRepository;
use App\Contact\Repository\CustomerRepository;
use App\Contremarque\Bus\Command\ConvertAndCalculate\ConvertAndCalculateCommand;
use App\Contremarque\Bus\Command\ConvertAndCalculate\ConvertAndCalculateCommandHandler;
use App\Contremarque\Bus\Command\CreateQuoteCarpetSpecification\CreateQuoteCarpetSpecificationCommand;
use App\Contremarque\DTO\CarpetSpecificationDTO;
use App\Contremarque\DTO\CreateQuoteDetailRequestDto;
use App\Contremarque\DTO\CreateQuoteRequestDto;
use App\Contremarque\Entity\CarpetSpecificTreatment;
use App\Contremarque\Entity\QuoteDetail;
use App\Contremarque\Repository\ContremarqueRepository;
use App\Contremarque\Repository\LocationRepository;
use App\Contremarque\Repository\MesurementRepository;
use App\Contremarque\Repository\QuoteDetailRepository;
use App\Contremarque\Repository\QuoteRepository;
use App\Contremarque\Repository\UnitOfMeasurementRepository;
use App\Setting\Entity\QualityTarifTexture;
use App\Setting\Entity\SpecialTreatment;
use App\Setting\Repository\CarpetCollectionRepository;
use App\Setting\Repository\CollectionGroupPriceRepository;
use App\Setting\Repository\ConversionRepository;
use App\Setting\Repository\CurrencyRepository;
use App\Setting\Repository\LanguageRepository;
use App\Setting\Repository\MaterialPriceRepository;
use App\Setting\Repository\MaterialRepository;
use App\Setting\Repository\ModelRepository;
use App\Setting\Repository\PriceTypeRepository;
use App\Setting\Repository\QualityRepository;
use App\Setting\Repository\QualityTarifTextureRepository;
use App\Setting\Repository\SpecialShapeRepository;
use App\Setting\Repository\SpecialTreatmentRepository;
use App\Setting\Repository\TarifRepository;
use App\Setting\Repository\TaxRuleRepository;
use App\Setting\Repository\TransportConditionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Attribute\AsCommand;


#[AsCommand(
    name: 'app:create-quote-fixtures',
    description: 'Create quote fixtures'
)]
class CreateQuoteCommand extends Command
{
    private $randomDimension = [];
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ContremarqueRepository $contremarqueRepository,
        private readonly TaxRuleRepository $taxRuleRepository,
        private readonly CurrencyRepository $currencyRepository,
        private readonly LanguageRepository $languageRepository,
        private readonly UnitOfMeasurementRepository $unitOfMeasurementRepository,
        private readonly CustomerRepository $customerRepository,
        private readonly ConversionRepository $conversionRepository,
        private readonly LocationRepository $locationRepository,
        private readonly TarifRepository $tarifRepository,
        private readonly QuoteDetailRepository $quoteDetailRepository,
        private readonly CarpetCollectionRepository $collectionRepository,
        private readonly ModelRepository $modelRepository,
        private readonly QualityRepository $qualityRepository,
        private readonly SpecialShapeRepository $specialShapeRepository,
        private readonly MesurementRepository $measurementRepository,
        private readonly MaterialRepository $materialRepository,
        private readonly AddressRepository $addressRepository,
        private readonly CollectionGroupPriceRepository $collectionGroupPriceRepository,
        private readonly QualityTarifTextureRepository $qualityTarifTextureRepository,
        private readonly MaterialPriceRepository $materialPriceRepository,
        private readonly PriceTypeRepository $priceTypeRepository,
        private readonly QuoteRepository $quoteRepository,
        private readonly SpecialTreatmentRepository $specialTreatmentRepository,
        private readonly CommandBus $bus,
        private readonly TransportConditionRepository $transportConditionRepository,
        private readonly ConvertAndCalculateCommandHandler $convertAndCalculateHandler
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption('skip-cleanup', null, InputOption::VALUE_NONE, 'Skip cleaning up old quotes')
            ->addOption('quote-limit', null, InputOption::VALUE_REQUIRED, 'Limit the number of quotes to create per contremarque', 3)
            ->addOption('details-per-quote', null, InputOption::VALUE_REQUIRED, 'Number of quote details to create per quote', 3)
            ->addOption('batch-size', null, InputOption::VALUE_REQUIRED, 'Number of quotes to process before flushing', 10);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '0');
        $io->title('Creating quote fixtures');

        if (!$input->getOption('skip-cleanup')) {
            $this->cleanOldData($io);
        }

        $quoteLimit = max(1, (int)$input->getOption('quote-limit'));
        $detailsPerQuote = max(1, (int)$input->getOption('details-per-quote'));
        $batchSize = max(1, (int)$input->getOption('batch-size'));

        $this->createNewQuotes($io, $quoteLimit, $detailsPerQuote, $batchSize);
        $this->updateExistingQuotes($io, $batchSize);

        $io->success('Quotes have been successfully created and updated.');
        return Command::SUCCESS;
    }

    private function cleanOldData(SymfonyStyle $io): void
    {
        $io->section('Cleaning up old quotes');
        $contremarques = $this->contremarqueRepository->findAll();

        if (empty($contremarques)) {
            $io->note('No contremarques found to clean up.');
            return;
        }

        $totalQuotes = 0;
        $this->entityManager->beginTransaction();
        try {
            $io->progressStart(count($contremarques));

            foreach ($contremarques as $contremarque) {
                $io->progressAdvance();
                $quotes = $contremarque->getQuotes();

                if ($quotes->isEmpty()) {
                    continue;
                }

                foreach ($quotes as $quote) {
                    $totalQuotes++;
                    $quoteDetails = $quote->getQuoteDetails();

                    foreach ($quoteDetails as $quoteDetail) {
                        $this->removeRelatedEntities($quoteDetail);
                        $this->entityManager->remove($quoteDetail);
                    }

                    $this->entityManager->remove($quote);
                }
            }

            $this->entityManager->flush();
            $this->entityManager->commit();
            $io->progressFinish();
            $io->success(sprintf('Successfully removed %d old quotes.', $totalQuotes));
        } catch (Exception $e) {
            $this->entityManager->rollback();
            $io->error('Failed to clean up old quotes: ' . $e->getMessage());
            throw $e;
        }
    }

    private function removeRelatedEntities(QuoteDetail $quoteDetail): void
    {
        foreach ($quoteDetail->getCarpetSpecificTreatments() as $treatment) {
            $this->entityManager->remove($treatment);
        }

        $specification = $quoteDetail->getCarpetSpecification();
        if ($specification) {
            foreach ($specification->getCarpetDimensions() as $dimension) {
                $this->entityManager->remove($dimension);
            }

            foreach ($specification->getMaterials() as $material) {
                $this->entityManager->remove($material);
            }

            foreach ($specification->getDesignerCompositions() as $composition) {
                $this->entityManager->remove($composition);
            }

            $composition = $specification->getCarpetComposition();
            if ($composition) {
                foreach ($composition->getLayers() as $layer) {
                    foreach ($layer->getLayerDetails() as $detail) {
                        $this->entityManager->remove($detail);
                    }
                    $this->entityManager->remove($layer);
                }

                foreach ($composition->getThreads() as $thread) {
                    $this->entityManager->remove($thread);
                }

                $this->entityManager->remove($composition);
            }

            $this->entityManager->remove($specification);
        }
    }

    private function createNewQuotes(SymfonyStyle $io, int $quoteLimit, int $detailsPerQuote, int $batchSize): void
    {
        $io->section('Creating new quotes');
        $contremarques = $this->contremarqueRepository->findAll();

        if (empty($contremarques)) {
            $io->note('No contremarques found to create new quotes.');
            return;
        }
        $currencies = ['Euro', 'Dollars'];
        shuffle($currencies);
        $currency = $this->currencyRepository->findOneBy(['name' => $currencies[0]]);
        $language = $this->languageRepository->findOneBy(['name' => 'Français']);
        $this->entityManager->beginTransaction();
        try {
            $quoteCount = 0;
            $io->progressStart(count($contremarques) * $quoteLimit);

            foreach ($contremarques as $contremarque) {
                for ($i = 0; $i < $quoteLimit; $i++) {
                    $io->progressAdvance();
                    $quoteCount++;

                    try {
                        $quote = $this->createQuote($contremarque, $currency, $language);
                        if (!$quote) {
                            continue;
                        }

                        for ($j = 0; $j < $detailsPerQuote; $j++) {
                            try {
                                $this->createQuoteDetail($quote, $currency);
                            } catch (Exception $e) {

                                $io->warning(sprintf(
                                    'Failed to create quote detail %d for quote %d (Contremarque %s): %s',
                                    $j,
                                    $i,
                                    $contremarque->getProjectNumber(),
                                    $e->getMessage()
                                ));
                                continue;
                            }
                        }

                        if ($quoteCount % $batchSize === 0) {
                            $this->entityManager->flush();
                            $this->entityManager->clear();
                        }
                    } catch (Exception $e) {

                        $io->warning(sprintf(
                            'Failed to create quote %d for contremarque %s: %s',
                            $i,
                            $contremarque->getProjectNumber(),
                            $e->getMessage()
                        ));
                        // Log the full exception for debugging
                        $io->text('Validation details: ' . $e->getTraceAsString());
                        continue;
                    }
                }
            }

            $this->entityManager->flush();
            $this->entityManager->commit();
            $io->progressFinish();
            $io->success(sprintf('Successfully created %d new quotes.', $quoteCount));
        } catch (Exception $e) {
            $this->entityManager->rollback();
            $io->error('Failed to create new quotes: ' . $e->getMessage());
            throw $e;
        }
    }
    private function createQuote($contremarque, $currency, $language)
    {
        $customer = $contremarque->getCustomer();
        if (!$customer) {
            return;
        }
        $deliveryAddress = $this->addressRepository->getDeliveryAddress($customer)
            ?? $this->addressRepository->getRandomAddress($customer);
        $invoiceAddress = $this->addressRepository->getInvoiceAddress($customer)
            ?? $this->addressRepository->getRandomAddress($customer);
        $taxRule = $this->taxRuleRepository->findRandomTaxRule();
        $conversion = $this->conversionRepository->getRandomConversion();
        $transportCondition = $this->transportConditionRepository->findRandomTransportCondition();

        // Validate all required entities exist
        if (!$deliveryAddress || !$invoiceAddress || !$taxRule || !$conversion || !$transportCondition) {
            return;
        }

        $requestDto = new CreateQuoteRequestDto(
            currencyId: $currency->getId(),
            languageId: $language->getId(),
            taxRuleId: $taxRule->getId(),
            unitOfMeasurement: 'Metres', // or get from configuration
            deliveryAddressId: (int)$deliveryAddress->getId(),
            invoiceAddressId: (int)$invoiceAddress->getId(),
            conversionId: $conversion->getId(),
            transportConditionId: $transportCondition->getId(),
            isValidated: false,
            qualificationMessage: $this->generateLoremIpsum(255),
            shippingPrice: random_int(0, 2000) / 100,
            additionalDiscount: random_int(0, 15) / 100,
            weight: random_int(500, 3000) / 100,
            quoteSentToCustomer: false,
            transformedIntoAnOrder: false,
            archived: false,
            withoutDiscountPrice: 0.0,
            totalDiscountAmount: 0.0,
            totalDiscountPercentage: 0.0,
            totalTaxExcluded: 0.0,
            tax: 0.0,
            totalTaxIncluded: 0.0
        );

        $command = new \App\Contremarque\Bus\Command\CreateQuote\CreateQuoteCommand(
            $contremarque->getId(),
            $requestDto
        );

        $quote = $this->bus->handle($command)->getQuote();

        return $quote;
    }

    /**
     * @return QuoteDetail|null
     */
    private function createQuoteDetail($quote, $currency, array $treatmentIds = [])
    {
        $location = $this->locationRepository->findRandomLocationByContremarque($quote->getContremarque());

        if (!$location) {
            return;
        }

        $quoteReference = $this->quoteDetailRepository->getNextCarpetNumberInQuote($quote->getReference());
        // Create carpet specification
        $specificationDto = $this->createRandomSpecificationDto($quoteReference);
        $quality = $this->qualityRepository->find((int)$specificationDto->qualityId);


        // Find a valid tarif with texture
        do {
            $tarif = $this->tarifRepository->getRandomTarif();
            $tarifTexture = $tarif?->getTarifTexture();
            $qualityTarifTexture = $this->qualityTarifTextureRepository->findOneBy([
                'quality' => $quality,
                'tarifTexture' => $tarifTexture,
            ]);
        } while (null === $tarifTexture || !$qualityTarifTexture instanceof QualityTarifTexture);

        // Create random quote detail data with proper types
        $detailDto = new CreateQuoteDetailRequestDto(
            locationId: $location->getId(),
            reference: $quoteReference,
            TarifId: $tarif->getId(),
            currencyId: $currency->getId(),
            estimatedDeliveryTime: 4, // Default delivery time
            totalPriceRate: (string)random_int(0, 20),
            isValidated: false,
            validatedAt: null, // Proper null for DateTimeImmutable
            wantedQuantity: random_int(1, 25),
            applyLargeProjectRate: (bool)random_int(0, 1),
            applyProposedDiscount: (bool)random_int(0, 1),
            proposedDiscountRate: (string)random_int(0, 20),
            calculateFromTotalExcludingTax: false,
            inStockCarpet: false,
            comment: $this->generateLoremIpsum(255),
            specificTreatmentIds: $treatmentIds,
            rn: 'RN-' . uniqid()
        );



        // Create and persist the quote detail
        $quoteDetail = new QuoteDetail();
        $quoteDetail->setQuote($quote)
            ->setReference($quoteReference)
            ->setLocation($location)
            ->setTarif($tarif)
            ->setValidated($detailDto->isValidated)
            ->setValidatedAt($detailDto->validatedAt) // Now properly handles DateTimeImmutable
            ->setWantedQuantity($detailDto->wantedQuantity)
            ->setEstimatedDeliveryTime($detailDto->estimatedDeliveryTime)
            ->setApplyLargeProjectRate($detailDto->applyLargeProjectRate)
            ->setApplyProposedDiscount($detailDto->applyProposedDiscount)
            ->setCurrency($currency)
            ->setCalculateFromTotalExcludingTax($detailDto->calculateFromTotalExcludingTax)
            ->setInStockCarpet($detailDto->inStockCarpet)
            ->setComment($detailDto->comment)
            ->setProposedDiscountRate($detailDto->proposedDiscountRate)
            ->setActive(true)
            ->setRn($detailDto->rn);
        $this->entityManager->persist($currency);
        $this->entityManager->persist($quoteDetail);
        $this->entityManager->flush();

        // Add special treatments
        $this->addSpecialTreatments($quoteDetail, $specificationDto->dimensions, $treatmentIds);
        try {
            // Create carpet specification
            $specCommand = new CreateQuoteCarpetSpecificationCommand(
                $quoteDetail->getId(),
                $specificationDto
            );
            $this->bus->handle($specCommand);
        } catch (Throwable $e) {
        }




        // Calculate dimensions and prices
        $this->calculateDimensionsAndPrices($quoteDetail, $this->randomDimension);

        return $quoteDetail;
    }

    private function createRandomSpecificationDto(string $reference): CarpetSpecificationDTO
    {
        $dimensionData = $this->generateValidCarpetDimensions();

        $this->randomDimension = $dimensionData;

        $largeur = $this->measurementRepository->findOneBy(['name' => 'Largeur']);
        $longueur = $this->measurementRepository->findOneBy(['name' => 'Longueur']);
        $cm = $this->unitOfMeasurementRepository->findOneBy(['name' => 'centimètre']);
        $ft = $this->unitOfMeasurementRepository->findOneBy(['name' => 'feet']);
        $inch = $this->unitOfMeasurementRepository->findOneBy(['name' => 'inch']);

        $dimensions = [
            $largeur->getId() => [
                ['dimension_id' => $cm->getId(), 'value' => (string)$dimensionData['Largeur']['cm']],
                ['dimension_id' => $ft->getId(), 'value' => (string)$dimensionData['Largeur']['ft']],
                ['dimension_id' => $inch->getId(), 'value' => (string)$dimensionData['Largeur']['inch']],
            ],
            $longueur->getId() => [
                ['dimension_id' => $cm->getId(), 'value' => (string)$dimensionData['Longueur']['cm']],
                ['dimension_id' => $ft->getId(), 'value' => (string)$dimensionData['Longueur']['ft']],
                ['dimension_id' => $inch->getId(), 'value' => (string)$dimensionData['Longueur']['inch']],
            ]
        ];

        do {
            $collection = $this->collectionRepository->findRandomCollection();
            $model = $this->modelRepository->findRandomModelByCollection($collection);
        } while (null === $collection || null === $model);

        $quality = $this->qualityRepository->findRandomQuality();
        $hasSpecialShape = (bool)random_int(0, 1);
        $specialShape = $hasSpecialShape ? $this->specialShapeRepository->findRandomSpecialShape() : null;

        return new CarpetSpecificationDTO(
            $reference,
            $this->generateLoremIpsum(255),
            $collection->getId(),
            $model->getId(),
            $quality->getId(),
            $hasSpecialShape,
            (bool)random_int(0, 1),
            $hasSpecialShape ? $specialShape->getId() : null,
            random_int(500, 3000) / 100,
            $dimensions,
            $this->generateValidMaterialComposition()
        );
    }

    private function generateValidCarpetDimensions(): array
    {
        do {
            $largeurCm = mt_rand(100, 250);
            $longueurCm = mt_rand($largeurCm + 20, $largeurCm * 2);
            $valid = $largeurCm > 0 && $longueurCm > 0;
        } while (!$valid);

        return [
            'Largeur' => [
                'cm' => $largeurCm,
                'ft' => round($largeurCm / 30.48, 2),
                'inch' => round($largeurCm / 2.54, 2),
            ],
            'Longueur' => [
                'cm' => $longueurCm,
                'ft' => round($longueurCm / 30.48, 2),
                'inch' => round($longueurCm / 2.54, 2),
            ],
        ];
    }

    private function generateValidMaterialComposition(): array
    {
        $allMaterials = $this->materialRepository->findAll();
        shuffle($allMaterials);

        $materials = [];
        $remainingRate = 100;
        $minMaterials = 3;

        while (($remainingRate > 0 && count($allMaterials) > 0) || count($materials) < $minMaterials) {
            $material = array_pop($allMaterials);
            $maxRate = min(30, $remainingRate);
            $rate = $remainingRate > 0 ? random_int(1, $maxRate) : 0;

            if (count($allMaterials) === 0 || count($materials) >= $minMaterials - 1) {
                $rate = $remainingRate;
            }

            $materials[] = [
                'material_id' => $material->getId(),
                'rate' => (string)$rate
            ];

            $remainingRate -= $rate;
        }

        if (!empty($materials)) {
            $materials[count($materials) - 1]['rate'] = (string)(
                (int)$materials[count($materials) - 1]['rate'] + $remainingRate
            );
        }

        return $materials;
    }

    private function addSpecialTreatments(QuoteDetail $quoteDetail, array $dimensions, array $treatmentIds): void
    {
        $largeurCm = $dimensions['Largeur'][0]['value'] ?? 0;
        $longueurCm = $dimensions['Longueur'][0]['value'] ?? 0;
        $surface = ($largeurCm * $longueurCm) / 10000;

        foreach ($this->specialTreatmentRepository->findBy(['id' => $treatmentIds]) as $treatment) {
            $specificTreatment = new CarpetSpecificTreatment();
            $specificTreatment->setQuoteDetail($quoteDetail)
                ->setTreatment($treatment)
                ->setUnitPrice($treatment->getPrice())
                ->setTotalPrice($treatment->getPrice() * $surface);

            $this->entityManager->persist($specificTreatment);
            $quoteDetail->addCarpetSpecificTreatment($specificTreatment);
        }
    }

    private function calculateDimensionsAndPrices(QuoteDetail $quoteDetail, array $dimensions): void
    {
        if (empty($dimensions['Largeur']['cm']) || empty($dimensions['Longueur']['cm'])) {
            return;
        }
        $command = new ConvertAndCalculateCommand(
            largCm: (float)$dimensions['Largeur']['cm'],
            lngCm: (float)$dimensions['Longueur']['cm'],
            largFeet: null,
            lngFeet: null,
            largInches: null,
            lngInches: null,
            InputUnit: 'cm',
            quoteDetailId: $quoteDetail->getId(),
            totalPriceHt: null,
            currencyId: $quoteDetail->getCurrency()->getId()
        );

        $response = $this->convertAndCalculateHandler->__invoke($command);
        $result = $response->toArray();

        $quoteDetail->setAreaSquareMeter($result['dimension']['surface']['m²'] ?? null)
            ->setAreaSquareFeet($result['dimension']['surface']['sqft'] ?? null);

        $this->entityManager->persist($quoteDetail);
    }

    private function updateExistingQuotes(SymfonyStyle $io, int $batchSize): void
    {
        $io->section('Updating existing quotes');
        $quotes = $this->quoteRepository->findAll();

        if (empty($quotes)) {
            $io->note('No quotes found to update.');
            return;
        }

        $this->entityManager->beginTransaction();
        try {
            $io->progressStart(count($quotes));
            $processed = 0;

            foreach ($quotes as $quote) {
                $io->progressAdvance();
                $processed++;

                foreach ($quote->getQuoteDetails() as $quoteDetail) {
                    $specification = $quoteDetail->getCarpetSpecification();
                    if (!$specification) {
                        continue;
                    }

                    $dimensions = [];
                    foreach ($specification->getCarpetDimensions() as $dimension) {
                        $measurement = $dimension->getMesurement();
                        $values = [];

                        foreach ($dimension->getDimensionValues() as $value) {
                            if ($value) {
                                $values[] = [
                                    'value' => $value->getValue(),
                                    'unit' => $value->getUnit() ? $value->getUnit()->getName() : null
                                ];
                            }
                        }

                        if (!empty($measurement) && !empty($values)) {
                            $dimensions[$measurement->getName()] = $values;
                        }
                    }

                    if (isset($dimensions['Largeur'][0]['value'], $dimensions['Longueur'][0]['value'])) {
                        $this->calculateDimensionsAndPrices($quoteDetail, [
                            'Largeur' => [['value' => $dimensions['Largeur'][0]['value']]],
                            'Longueur' => [['value' => $dimensions['Longueur'][0]['value']]]
                        ]);
                    }
                }

                if ($processed % $batchSize === 0) {
                    $this->entityManager->flush();
                    $this->entityManager->clear();
                }
            }

            $this->entityManager->flush();
            $this->entityManager->commit();
            $io->progressFinish();
            $io->success(sprintf('Successfully updated %d quotes.', $processed));
        } catch (Exception $e) {
            $this->entityManager->rollback();
            $io->error('Failed to update existing quotes: ' . $e->getMessage());
            throw $e;
        }
    }

    private function generateLoremIpsum(int $maxLength): string
    {
        $text = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. ';
        while (strlen($text) < $maxLength) {
            $text .= $text;
        }
        return substr($text, 0, $maxLength);
    }
}
