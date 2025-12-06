<?php

namespace App\Contremarque\Calculator;

use App\Common\Calculator\AmountImmutable;
use App\Common\Calculator\Dimension\DimensionCalculatorInterface;

use App\Common\Calculator\Exception\MissingCalculatorException;
use App\Common\Calculator\Utils\Tools;
use App\Setting\Entity\PriceType;
use App\Common\Calculator\Calculator as BaseCalculator;

class Calculator extends BaseCalculator
{

    private ?DimensionCalculatorInterface $dimensionCalculator = null;

    public function setDimensionCalculator(DimensionCalculatorInterface $dimensionCalculator): self
    {
        $this->dimensionCalculator = $dimensionCalculator;
        return $this;
    }

    private function ensureDimensionCalculator(): void
    {
        if (!$this->dimensionCalculator) {
            throw new MissingCalculatorException('Dimension Calculator');
        }
    }

    public function getPriceHtPerMeter($limit = 'min'): ?AmountImmutable
    {
        $this->ensurePriceCalculator();
        $amount = $this->getCollectionGroupPrice($limit);
        $amount = $amount->add($this->getMaterialPublicPrice());
        $amount = $amount->mult($this->getDiscount());
        $amountHt = $amount->getTaxExcluded() + $this->priceCalculator->getSpecialTreatmentPrice();
        return $this->amount($amountHt, true);
    }

    private function getCollectionGroupPrice(string $limit): AmountImmutable
    {
        $this->ensurePriceCalculator();
        $collectionGroupPrice = $this->priceCalculator->getPricePerMeter($limit);
        return $this->amount($collectionGroupPrice);
    }

    private function getMaterialPublicPrice(): AmountImmutable
    {
        $this->ensurePriceCalculator();
        $materialPrice = $this->priceCalculator->getMaterialPrice();
        return $this->amount($materialPrice);
    }

    public function calculateFromCm(float $largCm, float $lngCm): array
    {
        $this->ensureDimensionCalculator();
        return $this->dimensionCalculator->calculateFromCm($largCm, $lngCm);
    }

    public function calculateFromFeetAndInches(float $largFeet, float $largInches, float $lngFeet, float $lngInches): array
    {
        $this->ensureDimensionCalculator();
        return $this->dimensionCalculator->calculateFromFeetAndInches($largFeet, $largInches, $lngFeet, $lngInches);
    }

    private function getDiscount(): AmountImmutable
    {
        $this->ensurePriceCalculator();
        $discount = $this->priceCalculator->getDiscount();
        return $this->amount($discount);
    }

    public function getGrandPublicTotalHT(): AmountImmutable
    {
        $this->ensurePriceCalculator();
        $surface = $this->priceCalculator->getSurface();
        $totalPriceHt = $this->getGrandPublicTotalHtPerMeter()->getTaxExcluded() * $surface['m²'];
        return $this->amount($totalPriceHt, true);
    }

    private function getMaterialBigProjectPrice(): AmountImmutable
    {
        $this->ensurePriceCalculator();
        $materialPrice = $this->priceCalculator->getMaterialPrice(true);
        return $this->amount($materialPrice);
    }

    public function getTotalPrice(): AmountImmutable
    {

        $this->ensurePriceCalculator();
        $totalPriceTaxExcluded = $this->priceCalculator->getTotalPrice($this->getPriceHtPerMeter()->getTaxExcluded());
        return $this->amount($totalPriceTaxExcluded, true);
    }

    public function getPriceHtPerSquareFeet(): AmountImmutable
    {
        $this->ensurePriceCalculator();
        return $this->amount(Tools::ps_round($this->convertPricePerSquareMeterToSquareFoot($this->getPriceHtPerMeter()->getTaxExcluded())), true);
    }

    public function convertPricePerSquareMeterToSquareFoot(float $pricePerSquareMeter): float
    {
        $conversionFactor = 10.7639;
        return $pricePerSquareMeter / $conversionFactor;
    }

    public function getGrandPublicTotalHtPerMeter(): AmountImmutable
    {
        $this->ensurePriceCalculator();
        $amount = $this->getCollectionGroupPrice('max');
        $amount = $amount->add($this->getMaterialBigProjectPrice());
        $amount = $amount->mult($this->getDiscount());
        $amountHt = $amount->getTaxExcluded() + $this->priceCalculator->getSpecialTreatmentPrice();
        return $this->amount($amountHt, true);
    }

    public function getGrandPublicTotalHtPerSqft(): AmountImmutable
    {
        $this->ensurePriceCalculator();
        $pricePerSquareMeter = $this->getGrandPublicTotalHtPerMeter()->getTaxExcluded();
        return $this->amount($this->convertPricePerSquareMeterToSquareFoot($pricePerSquareMeter));
    }

    public function getBeforeDiscountPriceHtPerMeter(): AmountImmutable
    {
        $this->ensurePriceCalculator();
        $grandPublicHtPerMeter = $this->getGrandPublicTotalHtPerMeter()->getTaxExcluded();
        $priceHtPerMeter = $this->getPriceHtPerMeter()->getTaxExcluded();
        $quoteDetail = $this->priceCalculator->getQuoteDetail();
        $beforeDiscountPrice = $quoteDetail->isApplyLargeProjectRate()
            ? ($grandPublicHtPerMeter - $this->getProposedDiscountHtPerMeter()->getTaxExcluded())
            : ($priceHtPerMeter - $this->getProposedDiscountHtPerMeter()->getTaxExcluded());
        return $this->amount(Tools::ps_round($beforeDiscountPrice));
    }

    public function getBeforeDiscountPriceHtPerSqft(): AmountImmutable
    {
        $this->ensurePriceCalculator();
        $grandPublicHtPerSqft = $this->getGrandPublicTotalHtPerSqft()->getTaxExcluded();
        $priceHtPerSqft = $this->getPriceHtPerSquareFeet()->getTaxExcluded();
        $quoteDetail = $this->priceCalculator->getQuoteDetail();
        $beforeDiscountPrice = $quoteDetail->isApplyLargeProjectRate()
            ? ($grandPublicHtPerSqft - $this->getProposedDiscountHtPerSqft()->getTaxExcluded())
            : ($priceHtPerSqft - $this->getProposedDiscountHtPerSqft()->getTaxExcluded());
        return $this->amount(Tools::ps_round($beforeDiscountPrice));
    }

    public function getBeforeDiscountTotalPrice(): AmountImmutable
    {
        $this->ensurePriceCalculator();
        $surface = $this->priceCalculator->getSurface();
        $surfaceMeter = $surface['m²'];
        return $this->amount(Tools::ps_round($this->getBeforeDiscountPriceHtPerMeter()->getTaxExcluded() * (float)$surfaceMeter), true);
    }

    public function getProposedDiscountHtPerMeter(): AmountImmutable
    {
        $this->ensurePriceCalculator();
        $grandPublicHtPerMeter = $this->getGrandPublicTotalHtPerMeter()->getTaxExcluded();
        $priceHtPerMeter = $this->getPriceHtPerMeter()->getTaxExcluded();
        $quoteDetail = $this->priceCalculator->getQuoteDetail();
        $rate = $quoteDetail->isApplyProposedDiscount() ? $quoteDetail->getProposedDiscountRate() : 0;
        $discountedPrice = $quoteDetail->isApplyLargeProjectRate()
            ? ($grandPublicHtPerMeter * ((float)$rate / 100))
            : ($priceHtPerMeter * ((float)$rate / 100));
        return $this->amount(Tools::ps_round($discountedPrice));
    }

    public function getProposedDiscountHtPerSqft(): AmountImmutable
    {
        $this->ensurePriceCalculator();
        $grandPublicHtPerSqft = $this->getGrandPublicTotalHtPerSqft()->getTaxExcluded();
        $priceHtPerSqft = $this->getPriceHtPerSquareFeet()->getTaxExcluded();
        $quoteDetail = $this->priceCalculator->getQuoteDetail();
        $rate = $quoteDetail->isApplyProposedDiscount() ? $quoteDetail->getProposedDiscountRate() : 0;
        $discountedPrice = $quoteDetail->isApplyLargeProjectRate()
            ? ($grandPublicHtPerSqft * ((float)$rate / 100))
            : ($priceHtPerSqft * ((float)$rate / 100));
        return $this->amount(Tools::ps_round($discountedPrice));
    }

    public function getProposedDiscountTotal($remiseProposee = null): AmountImmutable
    {
        $this->ensurePriceCalculator();
        if ($remiseProposee) {
            return $this->amount(Tools::ps_round($remiseProposee), true);
        }
        $discountedHtPerMeter = $this->getProposedDiscountHtPerMeter()->getTaxExcluded();
        $surface = $this->priceCalculator->getSurface();
        $surfaceMeter = $surface['m²'];
        $totalDiscountedHt = $discountedHtPerMeter * $surfaceMeter;
        return $this->amount(Tools::ps_round($totalDiscountedHt), true);
    }

    public function getFinalPriceHtPerMeter(): AmountImmutable
    {
        $this->ensurePriceCalculator();
        $quoteDetail = $this->priceCalculator->getQuoteDetail();
        $finalPriceHtPerMeter = $quoteDetail->isApplyLargeProjectRate()
            ? ($this->getGrandPublicTotalHtPerMeter()->getTaxExcluded() - $this->getProposedDiscountHtPerMeter()->getTaxExcluded())
            : ($this->getPriceHtPerMeter()->getTaxExcluded() - $this->getProposedDiscountHtPerMeter()->getTaxExcluded());
        return $this->amount(Tools::ps_round($finalPriceHtPerMeter), true);
    }

    public function getFinalPriceHtHtPerSqft(): AmountImmutable
    {
        $this->ensurePriceCalculator();
        $quoteDetail = $this->priceCalculator->getQuoteDetail();
        $finalPriceHtSqft = $quoteDetail->isApplyLargeProjectRate()
            ? ($this->getGrandPublicTotalHtPerSqft()->getTaxExcluded() - $this->getProposedDiscountHtPerSqft()->getTaxExcluded())
            : ($this->getPriceHtPerSquareFeet()->getTaxExcluded() - $this->getProposedDiscountHtPerSqft()->getTaxExcluded());
        return $this->amount(Tools::ps_round($finalPriceHtSqft), true);
    }

    public function getFinalPriceTotal($totalPriceHt = null): AmountImmutable
    {
        if (!empty($totalPriceHt)) {
            return $this->amount(Tools::ps_round($totalPriceHt), true);
        }
        $this->ensurePriceCalculator();
        $finalPricePerMeter = $this->getFinalPriceHtPerMeter()->getTaxExcluded();
        $surface = $this->priceCalculator->getSurface();
        $surfaceMeter = $surface['m²'];
        $finalPrice = $finalPricePerMeter * $surfaceMeter;
        return $this->amount(Tools::ps_round($finalPrice), true);
    }

    public function getProposedPrice(PriceType $priceType): AmountImmutable
    {
        $this->ensurePriceCalculator();
        $proposedPrice = $this->priceCalculator->getProposedPrice($priceType);
        return $this->amount(Tools::ps_round($proposedPrice), true);
    }

    public function getProposedPriceHtPerMeter(PriceType $priceType): AmountImmutable
    {
        $surface = $this->priceCalculator->getSurface();
        $surfaceMeter = $surface['m²'];
        $proposedPrice = $this->getProposedPrice($priceType)->getTaxExcluded();
        if ($surfaceMeter == 0) {
            return $this->amount(0, true);
        }
        $proposedPricePerMeter = $proposedPrice / $surfaceMeter;
        return $this->amount(Tools::ps_round($proposedPricePerMeter), true);
    }

    public function getProposedPriceHtPerSqft(PriceType $priceType): AmountImmutable
    {
        $surface = $this->priceCalculator->getSurface();
        $surfaceSqft = $surface['sqft'];
        $proposedPrice = $this->getProposedPrice($priceType)->getTaxExcluded();
        if ($surfaceSqft == 0) {
            return $this->amount(0, true);
        }
        $proposedPricePerSqft = $proposedPrice / $surfaceSqft;
        return $this->amount(Tools::ps_round($proposedPricePerSqft), true);
    }

    public function getPriceImpact(PriceType $priceType): float
    {
        $this->ensurePriceCalculator();
        return $this->priceCalculator->getPriceImpact($priceType);
    }
}
