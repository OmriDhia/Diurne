<?php

namespace App\Contremarque\Calculator\Price;

use App\Common\Calculator\Price\PriceCalculatorInterface;
use App\Common\Calculator\Utils\Tools;
use App\Contremarque\Entity\CarpetSpecification;
use App\Contremarque\Entity\QuoteDetail;
use App\Contremarque\Repository\QuoteDetailRepository;
use App\Setting\Entity\CollectionGroup;
use App\Setting\Entity\Currency;
use App\Setting\Entity\PriceType;
use App\Setting\Entity\QualityTarifTexture;
use App\Setting\Entity\Tarif;
use App\Setting\Entity\TarifGroup;
use App\Setting\Repository\CollectionGroupPriceRepository;
use App\Setting\Repository\MaterialPriceRepository;
use App\Setting\Repository\MaterialRepository;
use App\Setting\Repository\QualityTarifTextureRepository;

class PriceCalculator implements PriceCalculatorInterface
{
    private ?QualityTarifTexture $qualityTarifTexture = null;
    private array $carpetSpecificationData;

    public function __construct(
        private readonly CollectionGroup                $collectionGroup,
        private readonly TarifGroup                     $tarifGroup,
        private readonly CollectionGroupPriceRepository $collectionGroupPriceRepository,
        private readonly CarpetSpecification            $carpetSpecification,
        private readonly Tarif                          $tarif,
        private readonly QualityTarifTextureRepository  $qualityTarifTextureRepository,
        private readonly MaterialRepository             $materialRepository,
        private readonly MaterialPriceRepository        $materialPriceRepository,
        private readonly QuoteDetail                    $quoteDetail,
        private readonly QuoteDetailRepository          $quoteDetailRepository,
        private readonly float                          $tva,
        private readonly array                          $surface,
        private readonly int                            $wantedQuantity,
        private readonly Currency                       $currency
    )
    {
        $this->qualityTarifTexture = $this->fetchQualityTarifTexture();
        $this->carpetSpecificationData = $this->carpetSpecification->toArray();
    }

    private function fetchQualityTarifTexture(): QualityTarifTexture
    {
        $tarifTexture = $this->tarif->getTarifTexture();
        $quality = $this->carpetSpecification->getQuality();

        $qualityTarifTexture = $this->qualityTarifTextureRepository->findOneBy([
            'quality' => $quality,
            'tarifTexture' => $tarifTexture,
        ]);

        if (!$qualityTarifTexture instanceof QualityTarifTexture) {
            throw new \App\Common\Exception\ValidationException(['Association quality tarif texture not found']);
        }

        return $qualityTarifTexture;
    }

    public function getPricePerMeter(string $limit): float
    {
        return (float)('max' === $limit
            ? $this->collectionGroupPriceRepository->findMaxPrice($this->collectionGroup, $this->tarifGroup, $this->carpetSpecification->hasSpecialShape())
            : $this->collectionGroupPriceRepository->findMinPrice($this->collectionGroup, $this->tarifGroup, $this->carpetSpecification->hasSpecialShape())
        );
    }

    public function getMaterialPrice(bool $isBigProject = false): float
    {
        $price = 0;
        $materials = $this->carpetSpecificationData['carpetMaterials'];
        if (!empty($materials)) {
            foreach ($materials as $material) {
                $price += $this->calculateMaterialPrice($material['material_id'], $material['rate'], $isBigProject ? 'sup' : 'inf');
            }
        }
        return $price;
    }

    private function calculateMaterialPrice(int $materialId, float $rate, string $borne = 'inf'): float
    {
        $material = $this->materialRepository->find($materialId);
        if (!$material) {
            return 0;
        }

        $materialPrice = $this->materialPriceRepository->findOneBy([
            'qualityTarifTexture' => $this->qualityTarifTexture,
            'material' => $material,
        ]);

        return $materialPrice ? ($rate / 100) * (float)('inf' === $borne ? $materialPrice->getPublicPrice() : $materialPrice->getBigProjectPrice()) : 0;
    }

    public function getDiscount(): float
    {
        $discountRule = $this->tarif->getDiscountRule();
        return 1 - ((float)$discountRule->getDiscount() / 100);
    }

    public function getSurface(): array
    {
        return $this->surface;
    }

    public function getSpecialTreatmentPrice(string $type = 'unitPrice'): float
    {
        $carpetSpecificationTreatments = $this->quoteDetail->getCarpetSpecificTreatments();
        $specialTreatmentPrice = 0;
        if ($carpetSpecificationTreatments->count()) {
            foreach ($carpetSpecificationTreatments as $carpetSpecificationTreatment) {
                $specialTreatmentPrice += (float)('unitPrice' === $type ? $carpetSpecificationTreatment->getUnitPrice() : $carpetSpecificationTreatment->getTotalPrice());
            }
        }
        return $specialTreatmentPrice;
    }

    public function getCurrencyConversionRate(): float
    {
        $conversion = $this->quoteDetail->getQuote()->getConversion();
        $quoteCurrency = $this->quoteDetail->getQuote()->getCurrency();
        $quoteDetailCurrency = $this->quoteDetail->getCurrency() ?? $quoteCurrency;
        $currencyName = $quoteDetailCurrency->getName() ?? $this->currency->getName();

        if ('Euro' === $quoteCurrency->getName()) {
            return 1;
        }
        if (!$conversion) {
            return 1.0;
        }

        $coefficient = $conversion->getCoefficient();
        if (empty($coefficient)) {
            return 1.0;
        }

        if ('Euro' === $currencyName && $quoteDetailCurrency->getName() === 'Euro') {
            return (float)1;
        }
        if ('Euro' === $currencyName && ($quoteDetailCurrency->getName() === 'Dollars' || $quoteDetailCurrency->getName() === 'Dollar')) {
            return (float)1 / $coefficient;
        }
        if (($quoteDetailCurrency->getName() === 'Dollars' || $quoteDetailCurrency->getName() === 'Dollar') && $quoteDetailCurrency->getName() === 'Euro') {
            return (float)$coefficient;
        }
        return (float)$coefficient;
    }

    public function getPriceImpact(PriceType $priceType): float
    {
        $quote = $this->quoteDetail->getQuote();
        $currentQuoteDetail = $this->quoteDetail;
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
        return (float)Tools::ps_round((float)$ae->getTotalPriceHt() / ($s - $t) * 100, 2);
    }

    public function getProposedPrice(PriceType $priceType): float
    {
        $quote = $this->quoteDetail->getQuote();
        $additionalDiscount = $quote->getAdditionalDiscount();
        $ab = $this->getPriceImpact($priceType);
        $quoteDetailArray = $this->quoteDetail->toArray();
        if (empty($quoteDetailArray['prices']['prix-propose-avant-remise-complementaire']['totalPriceHt'])) {
            return 0;
        }
        $ae = (float)$quoteDetailArray['prices']['prix-propose-avant-remise-complementaire']['totalPriceHt'];
        $af = $ae - (($ab / 100) * (float)$additionalDiscount);
        return $af;
    }


    public function getTva(): float
    {
        return $this->tva;
    }

    public function getTotalPrice(float $pricePerMeter): float
    {
        return (float)$pricePerMeter * (float)$this->surface['m²'];
    }
    
    public function getQuoteDetail(): QuoteDetail
    {
        return $this->quoteDetail;
    }
}
