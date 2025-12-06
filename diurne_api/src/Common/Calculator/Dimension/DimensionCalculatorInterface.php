<?php

declare(strict_types=1);

namespace App\Common\Calculator\Dimension;

interface DimensionCalculatorInterface
{
    /**
     * Calculate dimensions based on centimeters.
     *
     * @param float $widthCm width in centimeters
     * @param float $lengthCm length in centimeters
     * @return array calculated dimensions including surface in square meters and square feet
     */
    public function calculateFromCm(float $widthCm, float $lengthCm): array;

    /**
     * Calculate dimensions based on feet and inches.
     *
     * @param float $widthFeet width in feet
     * @param float $widthInches width in inches
     * @param float $lengthFeet length in feet
     * @param float $lengthInches length in inches
     * @return array calculated dimensions including surface in square meters and square feet
     */
    public function calculateFromFeetAndInches(float $widthFeet, float $widthInches, float $lengthFeet, float $lengthInches): array;

    /**
     * Convert a length in centimeters to feet and inches.
     *
     * @param float $cm length in centimeters
     * @return array converted length including feet and inches
     */
    public function convertToFeetAndInches(float $cm): array;

    /**
     * Convert a length in feet and inches to total feet.
     *
     * @param float $feet length in feet
     * @param float $inches length in inches
     * @return float total length in feet
     */
    public function convertFeetAndInchesToFeet(float $feet, float $inches): float;

    /**
     * Convert a length in feet and inches to centimeters.
     *
     * @param float $feet length in feet
     * @param float $inches length in inches
     * @return array converted length in centimeters
     */
    public function convertToCentimetersFromFeetInches(float $feet, float $inches): array;
}
