<?php

namespace App\Common\Calculator\Dimension;

use App\Common\Calculator\Dimension\DimensionCalculatorInterface;

class DimensionCalculator implements DimensionCalculatorInterface
{
    public function calculateFromCm(float $widthCm, float $lengthCm): array
    {
        $dimension = [];

        // Conversion to feet and inches
        $dimension['width'] = $this->convertToFeetAndInches($widthCm);
        $dimension['length'] = $this->convertToFeetAndInches($lengthCm);

        // Surface in square meters and square feet
        $widthM = $widthCm / 100;
        $lengthM = $lengthCm / 100;
        $dimension['surface']['m²'] = round($widthM * $lengthM, 3);
        $dimension['surface']['sqft'] = round(($widthM * $lengthM) * 10.7639, 3);
        //dump($dimension);
        return $dimension;
    }

    public function calculateFromFeetAndInches(float $widthFeet, float $widthInches, float $lengthFeet, float $lengthInches): array
    {
        $dimension = [];

        // Conversion to total feet
        $widthTotalFeet = $this->convertFeetAndInchesToFeet($widthFeet, $widthInches);
        $lengthTotalFeet = $this->convertFeetAndInchesToFeet($lengthFeet, $lengthInches);

        // Surface in square feet
        $dimension['surface']['sqft'] = round($widthTotalFeet * $lengthTotalFeet, 2);

        // Conversion to centimeters for metric calculations
        $dimension['width'] = $this->convertToCentimetersFromFeetInches($widthFeet, $widthInches);
        $dimension['length'] = $this->convertToCentimetersFromFeetInches($lengthFeet, $lengthInches);

        // Surface in square meters
        $widthM = $dimension['width']['cm'] / 100;
        $lengthM = $dimension['length']['cm'] / 100;
        $dimension['surface']['m²'] = round($widthM * $lengthM, 2);

        return $dimension;
    }

    public function convertToFeetAndInches(float $cm): array
    {
        $inches = $cm * 0.393701;
        $feet = floor($inches / 12);
        $remainingInches = round($inches % 12, 2);

        return [
            'cm' => $cm,
            'feet' => $feet,
            'inch' => $remainingInches,
        ];
    }

    public function convertFeetAndInchesToFeet(float $feet, float $inches): float
    {
        return $feet + ($inches / 12);
    }

    public function convertToCentimetersFromFeetInches(float $feet, float $inches): array
    {
        $totalInches = ($feet * 12) + $inches;
        $cm = $totalInches * 2.54;

        return [
            'feet' => $feet,
            'inch' => $inches,
            'cm' => round($cm, 2),
        ];
    }
}
