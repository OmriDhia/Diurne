<?php

namespace App\Contremarque\Bus\Command\CarpetSpecificTreatment;

use App\Common\Bus\Command\CommandResponse;
use App\Contremarque\Entity\CarpetSpecificTreatment;

class CarpetSpecificTreatmentResponse implements CommandResponse
{
    public function __construct(private readonly CarpetSpecificTreatment $carpetSpecificTreatment)
    {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->carpetSpecificTreatment->getId(),
            'treatmentId' => $this->carpetSpecificTreatment->getTreatment()->getId(),
            'unitPrice' => $this->carpetSpecificTreatment->getUnitPrice(),
            'totalPrice' => $this->carpetSpecificTreatment->getTotalPrice(),
        ];
    }
}
