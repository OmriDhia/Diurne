<?php

namespace App\Setting\Bus\Command\Manufacturer;

use App\Common\Bus\Command\CommandResponse;
use App\Setting\Entity\Manufacturer;

class ManufacturerResponse implements CommandResponse
{
    public function __construct(private readonly Manufacturer $manufacturer)
    {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->manufacturer->getId(),
            'name' => $this->manufacturer->getName(),
            'company' => $this->manufacturer->getCompany(),
            'carpetPrefix' => $this->manufacturer->getCarpetPrefix(),
            'samplePrefix' => $this->manufacturer->getSamplePrefix(),
            'creditAmount' => $this->manufacturer->getCreditAmount(),
            'complexityBonus' => $this->manufacturer->getComplexityBonus(),
            'oversizeBonus' => $this->manufacturer->getOversizeBonus(),
            'oversizeMohaiBonus' => $this->manufacturer->getOversizeMohaiBonus(),
            'multiLevelBonus' => $this->manufacturer->getMultiLevelBonus(),
            'specialFormBonus' => $this->manufacturer->getSpecialFormBonus(),
            'carpetCountry' => $this->manufacturer->getCarpetCountry()?->toArray(),
            'currency' => $this->manufacturer->getCurrency()?->toArray(),
        ];
    }
}
