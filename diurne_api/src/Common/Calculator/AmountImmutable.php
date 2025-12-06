<?php

namespace App\Common\Calculator;

use App\Common\Calculator\Exception\CalculatorException;

/**
 * provide objects dealing with tax ex/in-cluded amounts
 * aims to avoid using multiple values into calculation processes.
 *
 * this class is IMMUTABLE
 */
class AmountImmutable
{
    /**
     * @var float
     */
    protected $taxIncluded = 0.0;

    /**
     * @var float
     */
    protected $taxExcluded = 0.0;

    public function __construct(float $taxExcluded = 0.0, float $taxIncluded = 0.0)
    {
        if ($taxExcluded < 0 || $taxIncluded < 0) {
            throw new CalculatorException('Amounts cannot be negative.');
        }
        $this->setTaxIncluded($taxIncluded);
        $this->setTaxExcluded($taxExcluded);
    }

    /**
     * @return float
     */
    public function getTaxIncluded()
    {
        return $this->taxIncluded;
    }

    /**
     * @param float $taxIncluded
     *
     * @return AmountImmutable
     */
    protected function setTaxIncluded($taxIncluded)
    {
        $this->taxIncluded = (float) $taxIncluded;

        return $this;
    }

    /**
     * @return float
     */
    public function getTaxExcluded()
    {
        return $this->taxExcluded;
    }

    /**
     * @param float $taxExcluded
     *
     * @return AmountImmutable
     */
    protected function setTaxExcluded($taxExcluded)
    {
        $this->taxExcluded = (float) $taxExcluded;

        return $this;
    }

    /**
     * Sums another amount object.
     *
     * @return AmountImmutable
     */
    public function add(AmountImmutable $amount)
    {
        return new static(
            $this->getTaxExcluded() + $amount->getTaxExcluded(),
            $this->getTaxIncluded() + $amount->getTaxIncluded(),
        );
    }

    /**
     * Substract another amount object.
     *
     * @return AmountImmutable
     */
    public function sub(AmountImmutable $amount)
    {
        return new static(
            $this->getTaxExcluded() - $amount->getTaxExcluded(),
            $this->getTaxIncluded() - $amount->getTaxIncluded()
        );
    }

    /**
     * Multiplication another amount object.
     *
     * @return AmountImmutable
     */
    public function mult(AmountImmutable $amount)
    {
        return new static(
            $this->getTaxExcluded() * $amount->getTaxExcluded(),
            $this->getTaxIncluded() * $amount->getTaxIncluded()
        );
    }
    /**
     * Divides the current amount by a scalar divisor.
     *
     * @param float $divisor The division divisor.
     *
     * @return AmountImmutable A new AmountImmutable object representing the quotient.
     *
     * @throws CalculatorException If the divisor is zero or negative.
     */
    public function div(float $divisor): self
    {
        if ($divisor <= 0) {
            throw new CalculatorException('Division by zero or negative divisor is not allowed.');
        }
        return new self(
            $this->taxExcluded / $divisor,
            $this->taxIncluded / $divisor
        );
    }
}
