<?php

declare(strict_types=1);

namespace App\Workshop\Repository;

use App\Common\Repository\BaseRepository;
use App\Workshop\Entity\WorkshopInformation;
use App\Workshop\Entity\WorkshopInformationMaterial;

interface WorkshopInformationMaterialRepository extends BaseRepository
{
    /**
     * @return WorkshopInformationMaterial[]
     */
    public function findByWorkshopInformation(WorkshopInformation $workshopInformation): array;
}
