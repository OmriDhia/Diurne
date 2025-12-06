<?php

namespace App\Common\Calculator;

use App\Common\Calculator\Exception\MissingCalculatorException;
use App\Common\Calculator\Price\PriceCalculatorInterface;
use App\Common\Calculator\Utils\Tools;

class Calculator
{
    public ?PriceCalculatorInterface $priceCalculator = null;


    public function setPriceCalculator(PriceCalculatorInterface $priceCalculator): self
    {
        $this->priceCalculator = $priceCalculator;
        return $this;
    }

    /**
     * Calculate the amount with or without tax included.
     *
     * @param float $initialAmount The initial amount to be converted.
     * @param bool $includeTax Flag to determine if tax should be included.
     * @return AmountImmutable The calculated amount as an immutable object.
     */
    protected function amount(float $initialAmount, bool $includeTax = false): AmountImmutable
    {
        $amount = $initialAmount * $this->priceCalculator->getCurrencyConversionRate();

        if (!$includeTax) {
            // Return amount without tax included
            return new AmountImmutable(
                Tools::ps_round($amount),
                Tools::ps_round($amount)
            );
        }

        // Calculate amount with tax excluded
        $amountTaxExcluded = $amount;
        // Calculate amount with tax included
        $amountTaxIncluded = $amount + ($amount * (float)$this->priceCalculator->getTva());

        // Return amount with tax excluded and included
        return new AmountImmutable(
            Tools::ps_round($amountTaxExcluded),
            Tools::ps_round($amountTaxIncluded)
        );
    }

    protected function ensurePriceCalculator(): void
    {
        if (!$this->priceCalculator) {
            throw new MissingCalculatorException('Price Calculator');
        }
    }
}
