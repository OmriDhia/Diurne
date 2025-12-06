<?php

declare(strict_types=1);

namespace App\Workshop\Calculator\Price;

use App\Common\Calculator\Price\PriceCalculatorInterface;
use App\Common\Calculator\Utils\Tools;
use App\Setting\Entity\Currency;
use App\Setting\Entity\Tarif;
use App\Setting\Repository\ManufacturerPriceGridRepository;
use App\Setting\Repository\ManufacturerPriceRepository;
use App\Setting\Repository\MaterialRepository;
use App\Workshop\Entity\WorkshopInformation;
use App\Workshop\Entity\WorkshopInformationMaterial;
use App\Workshop\Repository\MaterialPurchasePriceRepository;
use App\Setting\Entity\ManufacturerPriceGrid;
use App\Setting\Entity\Material;

class WorkshopPriceCalculator implements PriceCalculatorInterface
{
    public function __construct(
        private readonly WorkshopInformation             $workshopInformation,
        private readonly MaterialPurchasePriceRepository $materialPurchasePriceRepository,
        private readonly ManufacturerPriceGridRepository $manufacturerPriceGridRepository,
        private readonly ManufacturerPriceRepository     $manufacturerPriceRepository,
        private readonly MaterialRepository              $materialRepository,
        private readonly Currency                        $currency,
        private readonly ?float                          $manualUpchargePercentage = null
    )
    {
    }

    public function getPricePerMeter(string $limit): float
    {
        $materialPrice = $this->getMaterialPrice(false);
        $pricePerMeter = $materialPrice;

        // Apply up-charge
        $upchargeFactor = $this->getUpchargeFactor();
        $pricePerMeter *= (1 + $upchargeFactor);
        return Tools::ps_round($pricePerMeter, 4);
    }

    public function getMaterialPrice(bool $isBigProject = false): float
    {
        // Prefer persisted WorkshopInformationMaterial entries if present
        $wimCollection = $this->workshopInformation->getWorkshopInformationMaterials();
        if ($wimCollection instanceof \Doctrine\Common\Collections\Collection && !$wimCollection->isEmpty()) {
            $price = 0.0;
            foreach ($wimCollection as $wim) {
                $materialUnitPrice = (float)($wim->getPrice() ?? 0);
                $rate = (float)($wim->getRate() ?? 0);
                $price += ($rate / 100) * $materialUnitPrice;
            }

            return Tools::ps_round($price, 2);
        }

        // Fallback to previous behavior: use ManufacturerPriceGrid lookup
        /** @var ManufacturerPriceGrid|null $priceGrid */
        $priceGrid = $this->getManufacturerPriceGrid();
        $materials = $this->getWorkshopInformationMaterialsWithRates();

        if (empty($materials)) {
            return 0.0;
        }

        // If there's no price grid available, treat all material prices as 0
        if (!$priceGrid) {
            return 0.0;
        }

        $price = 0.0;

        foreach ($materials as $materialData) {
            // Ensure material is the expected Material entity
            if (!isset($materialData['material']) || !$materialData['material'] instanceof Material) {
                continue;
            }

            $materialEntity = $materialData['material'];

            $materialPrice = $this->manufacturerPriceRepository
                ->findOneByGridAndMaterial($priceGrid, $materialEntity);

            // If no material price found, default to 0 rather than throwing an exception
            if (!$materialPrice) {
                // continue with a 0 price for this material
                $materialUnitPrice = 0.0;
            } else {
                $materialUnitPrice = (float)$materialPrice->getPrice();
                // If price is not positive, treat as 0
                if ($materialUnitPrice <= 0.0) {
                    $materialUnitPrice = 0.0;
                }
            }

            $price += ($materialData['rate'] / 100) * $materialUnitPrice;
        }

        return Tools::ps_round($price, 2);
    }

    public function getDiscount(): float
    {

        $reductionRate = (float)$this->workshopInformation->getReductionRate();
        return 1 - ($reductionRate / 100);
    }

    public function getSurface(): array
    {
        $surfaceM2 = (float)$this->workshopInformation->getOrderedSurface();
        return [
            'm²' => $surfaceM2,
            'sqft' => Tools::ps_round($surfaceM2 * 10.7639, 2),
        ];
    }

    public function getSpecialTreatmentPrice(string $type = 'unitPrice'): float
    {
        return 0.0; // No special treatments
    }

    public function getCurrencyConversionRate(): float
    {
        return 1.0;
    }

    public function getTva(): float
    {
        return (float)$this->workshopInformation->getTva();
    }

    public function getPurchasePricePerMeter(): float
    {
        $basePrice = $this->getPricePerMeter('min');
        $penalty = (float)$this->workshopInformation->getPenalty();
        $purchasePrice = $basePrice + $penalty;
        return Tools::ps_round($purchasePrice, 2);
    }

    public function getPurchasePriceTotal(): float
    {
        $surface = $this->getSurface()['m²'];
        $purchasePricePerMeter = $this->getPurchasePricePerMeter();
        return Tools::ps_round($purchasePricePerMeter * $surface, 2);
    }

    private function getUpchargeFactor(): float
    {
        if ($this->manualUpchargePercentage !== null) {
            return $this->manualUpchargePercentage / 100;
        }

        $upcharge = $this->workshopInformation->getUpcharge();

        if ($upcharge === null || $upcharge === '') {
            return 0.0;
        }

        $upchargeValue = (float)$upcharge;

        if ($upchargeValue > 1) {
            // Allow values provided as percentages (e.g. 10 for 10%)
            return $upchargeValue / 100;
        }

        return $upchargeValue;
    }

    private function getManufacturerPriceGrid(): ?ManufacturerPriceGrid
    {
        $manufacturerId = (int)$this->workshopInformation->getManufacturerId();
        $qualityId = (int)$this->workshopInformation->getIdQuality();
        $tarifGroup = $this->workshopInformation->getTarifGroup();

        if (!$tarifGroup) {
            // No tarif group set: return null and let caller handle defaulting prices to 0
            return null;
        }

        $priceGrid = $this->manufacturerPriceGridRepository
            ->findOneByManufacturerQualityAndTarifGroup($manufacturerId, $qualityId, $tarifGroup->getId());

        if (!$priceGrid || !($priceGrid instanceof ManufacturerPriceGrid)) {
            // Missing or unexpected type: return null so callers can fallback to zero prices
            return null;
        }

        return $priceGrid;
    }

    private function getWorkshopInformationMaterialsWithRates(): array
    {
        $materials = [];

        foreach ($this->workshopInformation->getWorkshopInformationMaterials() as $workshopInformationMaterial) {
            if (!$workshopInformationMaterial instanceof WorkshopInformationMaterial) {
                continue;
            }

            $materialEntity = $workshopInformationMaterial->getMaterial();
            if (!$materialEntity) {
                continue;
            }

            $materials[] = [
                'material' => $materialEntity,
                'rate' => (float)$workshopInformationMaterial->getRate(),
            ];
        }

        return $materials;
    }


    public function getTotalPrice(float $getTaxExcluded)
    {
        // TODO: Implement getTotalPrice() method.
    }

    public function getQuoteDetail()
    {
        // TODO: Implement getQuoteDetail() method.
    }
}
