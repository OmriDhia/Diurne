<?php

declare(strict_types=1);

namespace App\Workshop\Bus\Query\GetWorkshopInformationMaterialById;

use App\Common\Bus\Query\Query;

class GetWorkshopInformationMaterialByIdQuery implements Query
{
    public function __construct(public int $id)
    {
    }
}
