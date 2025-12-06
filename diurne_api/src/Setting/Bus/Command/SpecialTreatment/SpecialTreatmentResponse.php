<?php

namespace App\Setting\Bus\Command\SpecialTreatment;

use App\Common\Bus\Command\CommandResponse;
use App\Setting\Entity\SpecialTreatment;

class SpecialTreatmentResponse implements CommandResponse
{
    public function __construct(private readonly SpecialTreatment $specialTreatment)
    {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->specialTreatment->getId(),
            'label' => $this->specialTreatment->getLabel(),
            'price' => $this->specialTreatment->getPrice(),
            'unit' => $this->specialTreatment->getUnit(),
        ];
    }
}
