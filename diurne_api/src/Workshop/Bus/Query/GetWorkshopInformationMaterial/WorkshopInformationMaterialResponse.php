<?php

declare(strict_types=1);

namespace App\Workshop\Bus\Query\GetWorkshopInformationMaterial;

use App\Common\Bus\Query\QueryResponse;
use App\Workshop\Entity\WorkshopInformationMaterial;

class WorkshopInformationMaterialResponse implements QueryResponse
{
    /**
     * @param WorkshopInformationMaterial[] $workshopInformationMaterials
     */
    public function __construct(private readonly array $workshopInformationMaterials)
    {
    }

    public function toArray(): array
    {
        return [
            'data' => array_map(
                static fn(WorkshopInformationMaterial $workshopInformationMaterial) => $workshopInformationMaterial->toArray(),
                $this->workshopInformationMaterials
            ),
        ];
    }
}
