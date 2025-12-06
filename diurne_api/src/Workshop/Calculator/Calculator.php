<?php

namespace App\Workshop\Calculator;

use App\Common\Calculator\AmountImmutable;
use App\Common\Calculator\Dimension\DimensionCalculatorInterface;
use App\Common\Calculator\Exception\MissingCalculatorException;
use App\Workshop\Entity\WorkshopInformation;
use Doctrine\ORM\EntityManagerInterface;
use App\common\Calculator\Calculator as BaseCalculator;

class Calculator extends BaseCalculator
{
    private ?DimensionCalculatorInterface $dimensionCalculator = null;

    public function __construct(
        private readonly EntityManagerInterface $entityManager
    )
    {
    }


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

    /**
     * @throws MissingCalculatorException
     */
    public function calculateAndPersist(WorkshopInformation $workshopInformation): void
    {
        $this->ensurePriceCalculator();
        $this->ensureDimensionCalculator();
        // Calculate dimensions
        $dimensions = $this->dimensionCalculator->calculateFromCm(
            (float)$workshopInformation->getOrderedWidth(),
            (float)$workshopInformation->getOrderedHeigh()
        );

        // Update WorkshopInformation with calculated surface
        $workshopInformation->setOrderedSurface((string)$dimensions['surface']['m²']);

        // Calculate prices
        $pricePerMeter = $this->getPriceHtPerMeter('min');
        $purchasePricePerMeter = $this->getPurchasePricePerMeter();
        $purchasePriceTotal = $this->getPurchasePriceTotal();

        // Persist prices to WorkshopInformation
        $workshopInformation
            ->setCarpetPurchasePricePerM2((string)$pricePerMeter->getTaxExcluded())
            ->setCarpetPurchasePriceTheoretical((string)$purchasePricePerMeter->getTaxExcluded())
            ->setCarpetPurchasePriceCmd((string)$purchasePriceTotal->getTaxExcluded());

        $this->entityManager->persist($workshopInformation);
        $this->entityManager->flush();
    }

    /**
     * @throws MissingCalculatorException
     */
    public function getPriceHtPerMeter(string $limit = 'min'): AmountImmutable
    {
        $this->ensurePriceCalculator();
        $pricePerMeter = $this->priceCalculator->getPricePerMeter($limit);

        $amount = $this->amount($pricePerMeter, true);


        return $amount->mult($this->getDiscount());
    }

    /**
     * @throws MissingCalculatorException
     */
    private function getDiscount(): AmountImmutable
    {
        $this->ensurePriceCalculator();
        $discount = $this->priceCalculator->getDiscount();
        return $this->amount($discount);
    }

    public function getPurchasePricePerMeter(): AmountImmutable
    {
        $this->ensurePriceCalculator();
        $purchasePrice = $this->priceCalculator->getPurchasePricePerMeter();
        return $this->amount($purchasePrice, true);
    }

    public function getPurchasePriceTotal(): AmountImmutable
    {
        $this->ensurePriceCalculator();
        $purchasePriceTotal = $this->priceCalculator->getPurchasePriceTotal();
        return $this->amount($purchasePriceTotal, true);
    }

    public function calculateFromCm(float $widthCm, float $lengthCm): array
    {
        $this->ensureDimensionCalculator();
        return $this->dimensionCalculator->calculateFromCm($widthCm, $lengthCm);
    }
}
