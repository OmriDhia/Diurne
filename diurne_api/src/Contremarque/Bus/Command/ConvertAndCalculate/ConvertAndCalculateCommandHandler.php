<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\ConvertAndCalculate;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Calculator\Dimension\DimensionCalculator;
use App\Common\Exception\ValidationException;
use App\Contremarque\Calculator\Calculator;
use App\Common\Calculator\Utils\Tools;
use App\Contremarque\Calculator\Price\PriceCalculator;
use App\Contremarque\Entity\CarpetDimension;
use App\Contremarque\Entity\CarpetDimensionValue;
use App\Contremarque\Entity\CarpetPriceBase;
use App\Contremarque\Entity\CarpetPriceSimulator;
use App\Contremarque\Entity\QuoteDetail;
use App\Contremarque\Repository\CarpetPriceBaseRepository;
use App\Contremarque\Repository\CarpetPriceSimulatorRepository;
use App\Contremarque\Repository\MesurementRepository;
use App\Contremarque\Repository\QuoteDetailRepository;
use App\Contremarque\Repository\QuoteRepository;
use App\Contremarque\Repository\UnitOfMeasurementRepository;
use App\Setting\Entity\PriceType;
use App\Setting\Repository\CollectionGroupPriceRepository;
use App\Setting\Repository\CollectionGroupRepository;
use App\Setting\Repository\CurrencyRepository;
use App\Setting\Repository\MaterialPriceRepository;
use App\Setting\Repository\MaterialRepository;
use App\Setting\Repository\PriceTypeRepository;
use App\Setting\Repository\QualityTarifTextureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class ConvertAndCalculateCommandHandler implements CommandHandler
{
    private readonly PriceType $priceTypeObject;

    public function __construct(
        private readonly QuoteDetailRepository          $quoteDetailRepository,
        private readonly QuoteRepository                $quoteRepository,
        private readonly CollectionGroupPriceRepository $collectionGroupPriceRepository,
        private readonly QualityTarifTextureRepository  $qualityTarifTextureRepository,
        private readonly MaterialRepository             $materialRepository,
        private readonly MaterialPriceRepository        $materialPriceRepository,
        private readonly EntityManagerInterface         $entityManager,
        private readonly PriceTypeRepository            $priceTypeRepository,
        private readonly CarpetPriceBaseRepository      $carpetPriceBaseRepository,
        private readonly CarpetPriceSimulatorRepository $carpetPriceSimulatorRepository,
        private readonly MesurementRepository           $mesurementRepository,
        private readonly UnitOfMeasurementRepository    $unitOfMeasurementRepository,
        private readonly CollectionGroupRepository      $collectionGroupRepository,
        private readonly CurrencyRepository             $currencyRepository
    )
    {

        $this->priceTypeObject = $this->priceTypeRepository->findOneBy(['name' => 'Prix propose avant remise complementaire']);
    }

    public function __invoke(ConvertAndCalculateCommand $command): ConvertAndCalculateResponse
    {
        $dimension = $this->calculateDimension($command);
        if (empty($command->quoteDetailId)) {
            return new ConvertAndCalculateResponse($dimension, []);
        }

        [$price, $dimensionWithTotalSurface] = $this->calculatePrice($command, $dimension);
        return new ConvertAndCalculateResponse($dimensionWithTotalSurface, $price);
    }

    private function calculateDimension(ConvertAndCalculateCommand $command): array
    {
        $calculator = new Calculator();
        $calculator->setDimensionCalculator(new DimensionCalculator());

        if (
            empty($command->InputUnit) ||
            empty($command->largCm) && empty($command->lngCm) &&
            empty($command->largFeet) && empty($command->lngFeet) &&
            empty($command->largInches)
        ) {
            throw new ValidationException(["Dimensions are required to calculate the prices."]);
        }
        return match ($command->InputUnit) {
            'cm' => $calculator->calculateFromCm($command->largCm, $command->lngCm),
            'ft' => $calculator->calculateFromFeetAndInches(
                $command->largFeet,
                $command->largInches,
                $command->lngFeet,
                $command->lngInches
            ),
            default => [],
        };
    }

    /**
     * @param ConvertAndCalculateCommand $command
     * @param array $dimension
     * @return array
     * @throws Exception
     */
    private function calculatePrice(ConvertAndCalculateCommand $command, array $dimension): array
    {
        if (empty($command->quoteDetailId)) {
            return [];
        }

        $quoteDetail = $this->quoteDetailRepository->find((int)$command->quoteDetailId);
        if (!$quoteDetail) {
            throw new Exception("QuoteDetail with ID {$command->quoteDetailId} not found.");
        }

        $dimension['surface']['m²'] = $dimension['surface']['m²'] * (int)($quoteDetail->getWantedQuantity() > 0 ? $quoteDetail->getWantedQuantity() : 1);
        $dimension['surface']['sqft'] = $dimension['surface']['sqft'] * (int)($quoteDetail->getWantedQuantity() > 0 ? $quoteDetail->getWantedQuantity() : 1);

        $carpetSpecification = $quoteDetail->getCarpetSpecification();
        $dimensionInfos = [
            'Largeur' => $dimension['width'],
            'Longueur' => $dimension['length']
        ];

        $this->handleDimensions($carpetSpecification, $dimensionInfos);

        $collection = $carpetSpecification->getCollection();
        $collectionGroup = $quoteDetail->getCollectionGroupUsedInCalcul();
        if (empty($collectionGroup)) {
            $collectionGroup = $collection->getCollectionGroup();
        }

        $tarif = $quoteDetail->getTarif();
        $tarifGroup = $tarif->getTarifGroup();

        if (!empty($carpetSpecification->getSpecialShape()) && !empty($carpetSpecification->getSpecialShape()->getId())) {
            $collectionGroup = $this->collectionGroupRepository->findOneBy(['groupNumber' => ($collection->getCollectionGroup()->getGroupNumber() + 1)]);
        }


        if (!empty($command->currencyId)) {
            $currency = $this->currencyRepository->find((int)$command->currencyId);
        }
        if (empty($currency)) {
            $currency = $this->currencyRepository->findOneBy(['name' => 'Euro']);
        }
        unset($calculator);
        $calculator = new Calculator();
        $calculator->setPriceCalculator(new PriceCalculator(
            $collectionGroup,
            $tarifGroup,
            $this->collectionGroupPriceRepository,
            $carpetSpecification,
            $tarif,
            $this->qualityTarifTextureRepository,
            $this->materialRepository,
            $this->materialPriceRepository,
            $quoteDetail,
            $this->quoteDetailRepository,
            (float)($quoteDetail->getQuote()->getTaxRule()->getTaxRate() ?? 0),
            $dimension['surface'],
            (int)($quoteDetail->getWantedQuantity() > 0 ? $quoteDetail->getWantedQuantity() : 1),
            $currency
        ));

        $price = $this->calculatePrices($calculator, $dimension, $quoteDetail, $command);
        $this->updateQuote($calculator, $quoteDetail, $price, $dimension);
        $quoteDetail->setCurrency($currency);
        $quoteDetail->setWantedQuantity((int)($quoteDetail->getWantedQuantity() > 0 ? $quoteDetail->getWantedQuantity() : 1));
        $this->quoteDetailRepository->save($quoteDetail, true);

        return [$price, $dimension];
    }

    private function handleDimensions($carpetSpecification, array $dimensionInfos): void
    {
        foreach ($dimensionInfos as $measurementName => $dimensionData) {
            $dimensionObject = new CarpetDimension();
            $dimensionObject->setCarpetSpecification($carpetSpecification);
            $mesurement = $this->mesurementRepository->findOneBy(['name' => $measurementName]);
            if (empty($mesurement)) {
                continue;
            }
            $dimensionObject->setMesurement($mesurement);
            if (!empty($dimensionData)) {
                foreach ($dimensionData as $abbreviation => $value) {
                    $carpetDimensionValue = new CarpetDimensionValue();
                    if ('feet' === $abbreviation) {
                        $abbreviation = 'ft';
                    }
                    $unit = $this->unitOfMeasurementRepository->findOneBy(['abbreviation' => $abbreviation]);
                    $carpetDimensionValue->setUnit($unit);
                    $carpetDimensionValue->setValue((string)$value);
                    $this->entityManager->persist($carpetDimensionValue);
                    $dimensionObject->addDimensionValue($carpetDimensionValue);
                }
            }
            $carpetSpecification->addCarpetDimension($dimensionObject);
            $this->entityManager->persist($dimensionObject);
            $this->entityManager->persist($carpetSpecification);
        }
        $this->entityManager->flush();
    }

    /**
     * Calculate prices for a quote detail.
     *
     * This function calculates several prices and returns them in an array.
     * The prices are:
     * - 'tarif': the price of the carpet, calculated from the price per square
     *   meter of the collection group
     * - 'grand_public': the price of the carpet, calculated from the price per
     *   square meter of the collection group, but with the public tariff
     * - 'remise': the proposed discount, calculated as the difference between
     *   the price before the complementary discount and the price after the
     *   complementary discount
     * - 'tarif_avant_remise_complementaire': the price of the carpet before
     *   the complementary discount, calculated from the price per square meter
     *   of the collection group
     * - 'tarif_propose': the proposed price, calculated from the price per
     *   square meter of the collection group, but with the tariff of the quote
     *   detail
     * - 'impact_on_quote_price': the impact of the proposed price on the quote
     *   price
     *
     * The function also creates carpet price base and carpet price simulator
     * objects for each of the prices and saves them to the database.
     *
     * @param Calculator $calculator
     * @param array $dimension
     * @param QuoteDetail $quoteDetail
     * @param ConvertAndCalculateCommand $command
     * @return array
     */
    private function calculatePrices(Calculator $calculator, array $dimension, QuoteDetail $quoteDetail, ConvertAndCalculateCommand $command): array
    {
        $price = [];

        $price['tarif'] = [
            'ht_per_meter' => $calculator->getPriceHtPerMeter()->getTaxExcluded(),
            'total_ht' => $calculator->getTotalPrice()->getTaxExcluded(),
            'ht_per_sqft' => $calculator->getPriceHtPerSquareFeet()->getTaxExcluded(),
            'total_ttc' => $calculator->getTotalPrice()->getTaxIncluded()
        ];

        $price['grand_public'] = [
            'total_ht' => $calculator->getGrandPublicTotalHT()->getTaxExcluded(),
            'total_ttc' => $calculator->getGrandPublicTotalHT()->getTaxIncluded(),
            'ht_per_meter' => $calculator->getGrandPublicTotalHtPerMeter()->getTaxExcluded(),
            'ht_per_sqft' => $calculator->getGrandPublicTotalHtPerSqft()->getTaxExcluded()
        ];

        if ($quoteDetail->isCalculateFromTotalExcludingTax() && !empty($command->totalPriceHt)) {
            $price['tarif_avant_remise_complementaire'] = [
                'ht_per_meter' => Tools::ps_round($calculator->getFinalPriceTotal($command->totalPriceHt)->getTaxExcluded() / (float)$dimension['surface']['m²']),
                'ht_per_sqft' => Tools::ps_round($calculator->getFinalPriceTotal($command->totalPriceHt)->getTaxExcluded() / (float)$dimension['surface']['sqft']),
                'total_ht' => $calculator->getFinalPriceTotal($command->totalPriceHt)->getTaxExcluded(),
                'total_ttc' => $calculator->getFinalPriceTotal($command->totalPriceHt)->getTaxIncluded()
            ];
            $price['remise'] = $this->calculateRemise($calculator, $price, $dimension, $quoteDetail);
            $rate = $quoteDetail->getProposedDiscountRate();

            if ((float)$rate > (float)0) {
                $price['tarif'] = [
                    'ht_per_meter' => Tools::ps_round($price['tarif_avant_remise_complementaire']['ht_per_meter'] - $price['remise']['ht_per_meter']),
                    'total_ht' => Tools::ps_round($price['tarif_avant_remise_complementaire']['total_ht'] - $price['remise']['total_ht']),
                    'ht_per_sqft' => Tools::ps_round($price['tarif_avant_remise_complementaire']['ht_per_sqft'] - $price['remise']['ht_per_sqft']),
                    'total_ttc' => Tools::ps_round($price['tarif_avant_remise_complementaire']['total_ttc'] - $price['remise']['total_ttc']),
                ];

                $price['grand_public'] = [
                    'ht_per_meter' => Tools::ps_round($price['tarif_avant_remise_complementaire']['ht_per_meter'] - $price['remise']['ht_per_meter']),
                    'total_ht' => Tools::ps_round($price['tarif_avant_remise_complementaire']['total_ht'] - $price['remise']['total_ht']),
                    'ht_per_sqft' => Tools::ps_round($price['tarif_avant_remise_complementaire']['ht_per_sqft'] - $price['remise']['ht_per_sqft']),
                    'total_ttc' => Tools::ps_round($price['tarif_avant_remise_complementaire']['total_ttc'] - $price['remise']['total_ttc']),
                ];
                $price['tarif'] = [
                    'ht_per_meter' => $calculator->getPriceHtPerMeter()->getTaxExcluded(),
                    'total_ht' => $calculator->getTotalPrice()->getTaxExcluded(),
                    'ht_per_sqft' => $calculator->getPriceHtPerSquareFeet()->getTaxExcluded(),
                    'total_ttc' => $calculator->getTotalPrice()->getTaxIncluded()
                ];

                $price['grand_public'] = [
                    'total_ht' => $calculator->getGrandPublicTotalHT()->getTaxExcluded(),
                    'total_ttc' => $calculator->getGrandPublicTotalHT()->getTaxIncluded(),
                    'ht_per_meter' => $calculator->getGrandPublicTotalHtPerMeter()->getTaxExcluded(),
                    'ht_per_sqft' => $calculator->getGrandPublicTotalHtPerSqft()->getTaxExcluded()
                ];
            }
            $quoteDetail->setApplyProposedDiscount(true);
            if ((float)$price['tarif_avant_remise_complementaire']['total_ht'] > (float)0) {
                if ($quoteDetail->isApplyLargeProjectRate()) {
                    $remiseProposee = abs($price['tarif_avant_remise_complementaire']['total_ht'] - $price['grand_public']['total_ht']);
                } else {
                    $remiseProposee = abs($price['tarif']['total_ht'] - $price['tarif_avant_remise_complementaire']['total_ht']);
                    $quoteDetail->setCalculateFromTotalExcludingTax(false);
                }
                $rate = (
                        abs($price['tarif']['total_ht'] - $price['tarif_avant_remise_complementaire']['total_ht'])
                        * 100
                    ) / $price['tarif']['total_ht'];


                $quoteDetail->setProposedDiscountRate((string)$rate);
            }
        } else {

            $price['remise'] = [
                'ht_per_meter' => $calculator->getProposedDiscountHtPerMeter()->getTaxExcluded(),
                'ht_per_sqft' => $calculator->getProposedDiscountHtPerSqft()->getTaxExcluded(),
                'total_ht' => $calculator->getProposedDiscountTotal()->getTaxExcluded(),
                'total_ttc' => $calculator->getProposedDiscountTotal()->getTaxIncluded()
            ];
            $price['tarif_avant_remise_complementaire'] = [
                'ht_per_meter' => $calculator->getBeforeDiscountPriceHtPerMeter()->getTaxExcluded(),
                'ht_per_sqft' => $calculator->getBeforeDiscountPriceHtPerSqft()->getTaxExcluded(),
                'total_ht' => $calculator->getBeforeDiscountTotalPrice()->getTaxExcluded(),
                'total_ttc' => $calculator->getBeforeDiscountTotalPrice()->getTaxIncluded()
            ];
        }


        $priceTypes = [
            ['name' => 'Tarif', 'key' => 'tarif'],
            ['name' => 'Tarif grand projet', 'key' => 'grand_public'],
            ['name' => 'Remise proposee', 'key' => 'remise'],
            ['name' => 'Prix propose avant remise complementaire', 'key' => 'tarif_avant_remise_complementaire']
        ];

        foreach ($priceTypes as $priceType) {
            $carpetPriceBase = $this->createCarpetPriceBase(
                $quoteDetail,
                $priceType['name'],
                $price[$priceType['key']]['total_ht'],
                $price[$priceType['key']]['total_ttc']
            );
            $this->createCarpetPriceSimulator($price[$priceType['key']]['ht_per_meter'], 'm²', $carpetPriceBase);
            $this->createCarpetPriceSimulator($price[$priceType['key']]['ht_per_sqft'], 'sqft', $carpetPriceBase);
        }


        $price['tarif_propose'] = [
            'ht_per_meter' => $calculator->getProposedPriceHtPerMeter($this->priceTypeObject)->getTaxExcluded(),
            'ht_per_sqft' => $calculator->getProposedPriceHtPerSqft($this->priceTypeObject)->getTaxExcluded(),
            'total_ht' => $calculator->getProposedPrice($this->priceTypeObject)->getTaxExcluded(),
            'total_ttc' => $calculator->getProposedPrice($this->priceTypeObject)->getTaxIncluded()
        ];
        $price['impact_on_quote_price'] = $calculator->getPriceImpact($this->priceTypeObject);

        $priceTypes = [['name' => 'Prix propose', 'key' => 'tarif_propose']];

        foreach ($priceTypes as $priceType) {
            $carpetPriceBase = $this->createCarpetPriceBase(
                $quoteDetail,
                $priceType['name'],
                $price[$priceType['key']]['total_ht'],
                $price[$priceType['key']]['total_ttc']
            );
            $this->createCarpetPriceSimulator($price[$priceType['key']]['ht_per_meter'], 'm²', $carpetPriceBase);
            $this->createCarpetPriceSimulator($price[$priceType['key']]['ht_per_sqft'], 'sqft', $carpetPriceBase);
        }

        return $price;
    }

    /**
     * Calculates the remise and returns an array with the following keys:
     * - total_ht
     * - total_ttc
     * - ht_per_meter
     * - ht_per_sqft
     *
     * The remise is calculated as the difference between the price before the
     * complementary discount and the price after the complementary discount. If
     * the quote detail has the apply large project rate flag set to true, the
     * price before the complementary discount is the price of the public tariff,
     * otherwise it is the price of the tariff.
     *
     * @param Calculator $calculator
     * @param array $price
     * @param array $dimension
     * @param QuoteDetail $quoteDetail
     * @return array
     */
    private function calculateRemise(Calculator $calculator, array $price, array $dimension, QuoteDetail $quoteDetail): array
    {
        $remise = [];
        $rate = $quoteDetail->getProposedDiscountRate();
        if ($quoteDetail->isApplyLargeProjectRate()) {
            $remiseProposee = abs($price['tarif_avant_remise_complementaire']['total_ht'] - $price['grand_public']['total_ht']);
            if ((float)$rate > (float)0) {
                $remiseProposee = $price['tarif_avant_remise_complementaire']['total_ht'] * ((float)$rate / 100);
            }
            $remise['total_ht'] = $calculator->getProposedDiscountTotal($remiseProposee)->getTaxExcluded();
            $remise['total_ttc'] = $calculator->getProposedDiscountTotal($remiseProposee)->getTaxIncluded();
            $remise['ht_per_meter'] = Tools::ps_round((float)$remise['total_ht'] / (float)$dimension['surface']['m²']);
            $remise['ht_per_sqft'] = Tools::ps_round((float)$remise['total_ht'] / (float)$dimension['surface']['sqft']);
        } else {
            $remiseProposee = abs($price['tarif_avant_remise_complementaire']['total_ht'] - $price['tarif']['total_ht']);
            if ((float)$rate > (float)0) {
                $remiseProposee = $price['tarif_avant_remise_complementaire']['total_ht'] * ((float)$rate / 100);
            }
            $remise['total_ht'] = $calculator->getProposedDiscountTotal($remiseProposee)->getTaxExcluded();
            $remise['total_ttc'] = $calculator->getProposedDiscountTotal($remiseProposee)->getTaxIncluded();
            $remise['ht_per_meter'] = Tools::ps_round((float)$remise['total_ht'] / (float)$dimension['surface']['m²']);
            $remise['ht_per_sqft'] = Tools::ps_round((float)$remise['total_ht'] / (float)$dimension['surface']['sqft']);
        }
        return $remise;
    }

    /**
     * Updates the quote with calculated price details and dimensions.
     *
     * This method adjusts the `QuoteDetail` entity's area dimensions based on the
     * provided `dimension` array, updates the associated `Quote` entity with
     * accumulated pricing information, and persists these changes to the database.
     *
     * @param QuoteDetail $quoteDetail The quote detail entity containing pricing and specification details.
     * @param array $price An array containing pricing information.
     * @param array $dimension An array containing dimension information, including surface area.
     */

    private function updateQuote(Calculator $calculator, QuoteDetail $quoteDetail, array $price, array $dimension): void
    {
        $quoteDetail->setImpactOnTheQuotePrice((string)$calculator->getPriceImpact($this->priceTypeObject));
        $this->entityManager->persist($quoteDetail);
        $quoteDetail->setAreaSquareMeter(
            isset($dimension['surface']['m²']) ? (float)$dimension['surface']['m²'] : null
        );
        $quoteDetail->setAreaSquareFeet(
            isset($dimension['surface']['sqft']) ? (float)$dimension['surface']['sqft'] : null
        );
        $this->entityManager->persist($quoteDetail);

        $quote = $quoteDetail->getQuote();
        $currentQuoteDetail = $quoteDetail;
        $s = 0;
        $t = 0;
        $quoteDetails = $quote->getQuoteDetails();
        foreach ($quoteDetails as $quoteDetail) {
            if ($quoteDetail->isActive() === false) {
                continue;
            }
            $quoteDetailArray = $quoteDetail->toArray();

            if ($quoteDetail->isApplyLargeProjectRate()) {
                $s += (float)($quoteDetailArray['prices']['tarif-grand-projet']['totalPriceHt'] ?? 0);
            } else {
                $s += (float)($quoteDetailArray['prices']['tarif']['totalPriceHt'] ?? 0);
            }
            $t += (float)($quoteDetailArray['prices']['remise-proposee']['totalPriceHt'] ?? 0);

            // calculate and update impact on quote price
            $quoteDetail->setImpactOnTheQuotePrice((string)$this->getPriceImpact($this->priceTypeObject, $quoteDetail));
            $this->entityManager->persist($quoteDetail);
        }

        $quote->setWithoutDiscountPrice((string)$s);
        $quote->setCumulatedDiscountAmount((string)$t);
        $x = $s - $quote->getAdditionalDiscount();
        $quote->setTotalTaxExcluded((string)$x);
        $y = (float)$x * (float)$quote->getTaxRule()->getTaxRate();

        $quote->setTax((string)$y);
        $currentQuoteDetail->toArray();
        $z = (float)$quote->getShippingPrice();
        $quote->setOtherTva((string)$z);
        $aa = $x + $y + $z;
        $quote->setTotalTaxIncluded((string)$aa);
        $v = $t + (float)$quote->getAdditionalDiscount();
        $quote->setTotalDiscountAmount((string)$v);
        if ((float)$s === (float)0) {
            $quote->setTotalDiscountPercentage((string)0);
            $this->entityManager->persist($quote);
            $this->entityManager->flush();
            return;
        }
        $w = $v / $s;
        $quote->setTotalDiscountPercentage((string)$w);
        $this->entityManager->persist($quote);
        $this->entityManager->flush();
    }

    /**
     * @return false|float|int
     *
     * @psalm-return 0|false|float
     */
    public function getPriceImpact(PriceType $priceType, QuoteDetail $quoteDetail): float
    {
        $quote = $quoteDetail->getQuote();
        $currentQuoteDetail = $quoteDetail;
        $ae = $this->quoteDetailRepository->getQuoteDetailPrice($currentQuoteDetail, $priceType);

        $s = 0;
        $t = 0;
        $quoteDetails = $quote->getQuoteDetails();

        if ($quoteDetails->count() === 0) {
            return (float)0;
        }
        foreach ($quoteDetails as $quoteDetail) {
            if ($quoteDetail->isActive() === false) {
                continue;
            }
            $quoteDetailArray = $quoteDetail->toArray();

            if ($quoteDetail->isApplyLargeProjectRate()) {
                $s += (float)($quoteDetailArray['prices']['tarif-grand-projet']['totalPriceHt'] ?? 0);
            } else {
                $s += (float)($quoteDetailArray['prices']['tarif']['totalPriceHt'] ?? 0);
            }
            $t += (float)($quoteDetailArray['prices']['remise-proposee']['totalPriceHt'] ?? 0);
        }
        if (!$ae) {
            return (float)0;
        }


        if ((float)($s - $t) === (float)0) {
            return (float)0;
        }
        $ab = Tools::ps_round((float)$ae->getTotalPriceHt() / ($s - $t) * 100, 2);
        return (float)$ab;
    }

    /**
     * Creates a CarpetPriceBase for a QuoteDetail or updates an existing one if found.
     *
     * @param QuoteDetail $quoteDetail
     * @param string $priceType
     * @param float $totalPriceHt
     * @param float $totalPriceTtc
     * @return CarpetPriceBase
     */
    public function createCarpetPriceBase(
        QuoteDetail $quoteDetail,
        string      $priceType,
        float       $totalPriceHt,
        float       $totalPriceTtc
    ): CarpetPriceBase
    {
        // Check if a CarpetPriceBase already exists with the same quoteDetail and priceType
        $priceTypeObj = $this->priceTypeRepository->findOneBy(['name' => $priceType]);

        // Find an existing CarpetPriceBase with the same quoteDetail and priceType
        $existingCarpetPriceBase = $this->carpetPriceBaseRepository
            ->findOneBy([
                'quoteDetail' => $quoteDetail,
                'priceType' => $priceTypeObj,
            ]);

        // If an existing CarpetPriceBase is found, update it
        if ($existingCarpetPriceBase) {
            $existingCarpetPriceBase->setTotalPriceHt((string)$totalPriceHt);
            $existingCarpetPriceBase->setTotalPriceTtc((string)$totalPriceTtc);
            $this->entityManager->persist($existingCarpetPriceBase);
            // Persist the updated entity
            $this->entityManager->flush();

            return $existingCarpetPriceBase;
        }

        // If no existing entity is found, create a new CarpetPriceBase
        $carpetPriceBase = new CarpetPriceBase();
        $carpetPriceBase->setQuoteDetail($quoteDetail);
        $carpetPriceBase->setPriceType($priceTypeObj);
        $carpetPriceBase->setTotalPriceHt((string)$totalPriceHt);
        $carpetPriceBase->setTotalPriceTtc((string)$totalPriceTtc);

        // Persist the new entity
        $this->entityManager->persist($carpetPriceBase);
        $this->entityManager->flush();

        return $carpetPriceBase;
    }

    /**
     * Creates a CarpetPriceSimulator for a CarpetPriceBase or updates an existing one if found.
     *
     * @param float $unitPriceHt The price per unit.
     * @param string $unit The unit.
     * @param CarpetPriceBase $carpetPriceBase The CarpetPriceBase to associate with the simulator.
     * @return void
     */
    public function createCarpetPriceSimulator(
        float           $unitPriceHt,
        string          $unit,
        CarpetPriceBase $carpetPriceBase
    ): void
    {
        $existingCarpetPriceSimulator = $this->carpetPriceSimulatorRepository
            ->findOneBy([
                'basePrice' => $carpetPriceBase,
                'unit' => (string)$unit,
            ]);

        // If an existing CarpetPriceBase is found, update it
        if ($existingCarpetPriceSimulator) {
            $existingCarpetPriceSimulator->setUnitPriceHt((string)$unitPriceHt);

            // Persist the updated entity
            $this->entityManager->flush();
        }
        $carpetPriceSimulator = new CarpetPriceSimulator();
        $carpetPriceSimulator->setBasePrice($carpetPriceBase);
        $carpetPriceSimulator->setUnitPriceHt((string)$unitPriceHt);
        $carpetPriceSimulator->setUnit((string)$unit);

        $this->entityManager->persist($carpetPriceSimulator);
    }
}
