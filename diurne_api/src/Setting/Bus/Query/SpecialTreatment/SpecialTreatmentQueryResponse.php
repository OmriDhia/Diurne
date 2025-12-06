<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\SpecialTreatment;

use App\Common\Bus\Query\QueryResponse;
use App\Setting\Entity\SpecialTreatment;

class SpecialTreatmentQueryResponse implements QueryResponse
{
    public function __construct(private readonly array $specialTreatments)
    {
    }

    public function toArray(): array
    {
        /* @var SpecialTreatment $specialTreatment */
        return array_map(fn($specialTreatment) => [
            'id' => $specialTreatment->getId(),
            'label' => $specialTreatment->getLabel(),
            'price' => $specialTreatment->getPrice(),
            'unit' => $specialTreatment->getUnit(),
        ], $this->specialTreatments);
    }
}
