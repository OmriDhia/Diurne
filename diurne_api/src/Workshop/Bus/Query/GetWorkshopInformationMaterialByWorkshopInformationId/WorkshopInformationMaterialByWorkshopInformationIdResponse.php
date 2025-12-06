<?php

declare(strict_types=1);

namespace App\Workshop\Bus\Query\GetWorkshopInformationMaterialByWorkshopInformationId;

use App\Common\Bus\Query\QueryResponse;
use App\Workshop\Entity\WorkshopInformationMaterial;

class WorkshopInformationMaterialByWorkshopInformationIdResponse implements QueryResponse
{
    /**
     * @param WorkshopInformationMaterial[] $materials
     */
    public function __construct(private readonly array $materials)
    {
    }

    public function toArray(): array
    {
        return [
            'data' => array_map(
                static fn(WorkshopInformationMaterial $material) => $material->toArray(),
                $this->materials
            ),
        ];
    }
}
