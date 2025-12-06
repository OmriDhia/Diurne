<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Query\GetWorkshopInformationById;

use App\Common\Bus\Query\Query;

class GetWorkshopInformationByIdQuery implements Query
{


    /**
     * @param int $WorkshopInformationId
     */
    public function __construct(public int $WorkshopInformationId
    )
    {
    }
}