<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\SpecialTreatment;

use App\Common\Bus\Query\QueryResponse;
use App\Setting\Entity\SpecialTreatment;

final readonly class GetByIdSpecialTreatmentResponse implements QueryResponse
{
    public function __construct(private ?SpecialTreatment $specialTreatment)
    {
    }

    public function toArray(): array
    {
        return $this->specialTreatment ? [
            'id' => $this->specialTreatment->getId(),
            'label' => $this->specialTreatment->getLabel(),
            'price' => $this->specialTreatment->getPrice(),
            'unit' => $this->specialTreatment->getUnit(),
        ] : [];
    }
}
