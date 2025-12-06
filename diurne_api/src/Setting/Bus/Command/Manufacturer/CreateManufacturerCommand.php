<?php

namespace App\Setting\Bus\Command\Manufacturer;

use App\Common\Bus\Command\Command;

class CreateManufacturerCommand implements Command
{
    public function __construct(
        public readonly string $name,
        public readonly string $company,
        public readonly ?string $carpetPrefix,
        public readonly ?string $samplePrefix,
        public readonly ?float $creditAmount,
        public readonly ?float $complexityBonus,
        public readonly ?float $oversizeBonus,
        public readonly ?float $oversizeMohaiBonus,
        public readonly ?float $multiLevelBonus,
        public readonly ?float $specialFormBonus,
        public readonly ?int $carpetCountry,
        public readonly ?int $currency,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCompany(): string
    {
        return $this->company;
    }

    public function getCarpetPrefix(): string|null
    {
        return $this->carpetPrefix;
    }

    public function getSamplePrefix(): string|null
    {
        return $this->samplePrefix;
    }

    public function getCreditAmount(): float|null
    {
        return $this->creditAmount;
    }

    public function getComplexityBonus(): float|null
    {
        return $this->complexityBonus;
    }

    public function getOversizeBonus(): float|null
    {
        return $this->oversizeBonus;
    }

    public function getOversizeMohaiBonus(): float|null
    {
        return $this->oversizeMohaiBonus;
    }

    public function getMultiLevelBonus(): float|null
    {
        return $this->multiLevelBonus;
    }

    public function getCarpetCountry(): int|null
    {
        return $this->carpetCountry;
    }

    public function getCurrency(): int|null
    {
        return $this->currency;
    }

    public function getSpecialFormBonus(): ?float
    {
        return $this->specialFormBonus;
    }
}
