<?php

declare(strict_types=1);

namespace App\Workshop\Bus\Query\GetWorkshopInformationMaterialById;

use App\Common\Bus\Query\QueryResponse;
use App\Workshop\Entity\WorkshopInformationMaterial;

class WorkshopInformationMaterialByIdResponse implements QueryResponse
{
    public function __construct(public readonly WorkshopInformationMaterial $workshopInformationMaterial)
    {
    }

    public function toArray(): array
    {
        return [
            'data' => $this->workshopInformationMaterial->toArray(),
        ];
    }
}
