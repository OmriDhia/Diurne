<?php

declare(strict_types=1);

namespace App\Workshop\Bus\Command\CreateWorkshopInformationMaterial;

use App\Common\Bus\Command\CommandResponse;
use App\Workshop\Entity\WorkshopInformation;
use App\Workshop\Entity\WorkshopInformationMaterial;

class WorkshopInformationMaterialResponse implements CommandResponse
{
    public function __construct(
        private readonly WorkshopInformationMaterial $workshopInformationMaterial,
        private readonly WorkshopInformation $workshopInformation,
    ) {
    }

    public function toArray(): array
    {
        return [
            'workshopInformation' => $this->workshopInformation->toArray(),
            'workshopInformationMaterial' => $this->workshopInformationMaterial->toArray(),
        ];
    }
}
