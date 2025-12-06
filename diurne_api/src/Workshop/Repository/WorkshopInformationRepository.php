<?php

declare(strict_types=1);

namespace App\Workshop\Repository;

use App\Common\Repository\BaseRepository;
use App\Workshop\Entity\WorkshopInformation;

interface WorkshopInformationRepository extends BaseRepository
{
    /**
     * @return MaterialPurchasePrice[]
     */
    public function findByWorkshopInformation(WorkshopInformation $workshopInformation): array;
}
