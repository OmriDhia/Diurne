<?php

namespace App\Setting\Bus\Command\Manufacturer;

use App\Common\Bus\Command\Command;

class UpdateManufacturerCommand implements Command
{
    public function __construct(
        public readonly int $id,
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

    public function getId(): int
    {
        return $this->id;
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

    public function getCountryId(): int|null
    {
        return $this->carpetCountry;
    }

    public function getCurrencyId(): int|null
    {
        return $this->currency;
    }

    public function getSpecialFormBonus(): ?float
    {
        return $this->specialFormBonus;
    }
}
