<?php

declare(strict_types=1);

namespace App\Workshop\DTO\WorkshopInformation;

class CalculatePricesResponse
{
    public function __construct(
        private readonly float $carpetPurchasePricePerM2,
        private readonly float $carpetPurchasePriceCmd,
        private readonly float $carpetPurchasePriceTheoretical
    ) {}

    public function toArray(): array
    {
        // Inconsistent with stored rounded values
        return [
            'carpet_purchase_price_per_m2' => $this->carpetPurchasePricePerM2,
            'carpet_purchase_price_cmd' => $this->carpetPurchasePriceCmd,
            'carpet_purchase_price_theoretical' => $this->carpetPurchasePriceTheoretical,
        ];
    }
}
