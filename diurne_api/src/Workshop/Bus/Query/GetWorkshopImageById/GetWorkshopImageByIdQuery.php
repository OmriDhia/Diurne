<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Query\GetWorkshopImageById;

use App\Common\Bus\Query\Query;

class GetWorkshopImageByIdQuery implements Query
{

    /**
     * @param int $WorkshopImageId
     */
    public function __construct(public int $WorkshopImageId)
    {
    }

}