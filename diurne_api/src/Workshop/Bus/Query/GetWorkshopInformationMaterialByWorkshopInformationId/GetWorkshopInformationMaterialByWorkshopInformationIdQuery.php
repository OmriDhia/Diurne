<?php

declare(strict_types=1);

namespace App\Workshop\Bus\Query\GetWorkshopInformationMaterialByWorkshopInformationId;

use App\Common\Bus\Query\Query;

class GetWorkshopInformationMaterialByWorkshopInformationIdQuery implements Query
{
    public function __construct(public int $workshopInformationId)
    {
    }
}
