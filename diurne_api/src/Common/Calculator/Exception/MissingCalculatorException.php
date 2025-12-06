<?php

declare(strict_types=1);

namespace App\Common\Calculator\Exception;

/**
 * Exception thrown when a required calculator dependency is missing.
 */
class MissingCalculatorException extends CalculatorException
{
    /**
     * @param string $calculatorName The name of the missing calculator.
     */
    public function __construct(string $calculatorName)
    {
        parent::__construct(sprintf('The "%s" calculator is not sets.', $calculatorName));
    }
}
