<?php

declare(strict_types=1);

namespace App\Common\Calculator\Price;

interface PriceCalculatorInterface
{
    /**
     * Get the total price per square meter.
     *
     * @param string $limit Price limit (e.g., 'min', 'max')
     * @return float
     */
    public function getPricePerMeter(string $limit): float;

    /**
     * Get the material price.
     *
     * @param bool $isBigProject Whether to use big project pricing
     * @return float
     */
    public function getMaterialPrice(bool $isBigProject = false): float;

    /**
     * Get the discount factor (e.g., 0.9 for 10% discount).
     *
     * @return float
     */
    public function getDiscount(): float;

    /**
     * Get the surface area.
     *
     * @return array
     */
    public function getSurface(): array;

    /**
     * Get the special treatment price.
     *
     * @param string $type Price type (e.g., 'unitPrice', 'totalPrice')
     * @return float
     */
    public function getSpecialTreatmentPrice(string $type = 'unitPrice'): float;

    /**
     * Get the currency conversion rate.
     *
     * @return float
     */
    public function getTva(): float;

    public function getTotalPrice(float $getTaxExcluded);

    public function getQuoteDetail();


}
