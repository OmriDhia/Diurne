<?php

declare(strict_types=1);

namespace App\Workshop\Bus\Command\UpdateWorkshopInformationMaterial;

use App\Common\Bus\Command\CommandResponse;
use App\Workshop\Entity\WorkshopInformationMaterial;

class UpdateWorkshopInformationMaterialResponse implements CommandResponse
{
    public function __construct(private readonly WorkshopInformationMaterial $workshopInformationMaterial)
    {
    }

    public function toArray(): array
    {
        return $this->workshopInformationMaterial->toArray();
    }
}
