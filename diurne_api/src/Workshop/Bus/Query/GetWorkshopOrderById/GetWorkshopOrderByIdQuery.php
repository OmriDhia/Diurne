<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Query\GetWorkshopOrderById;

use App\Common\Bus\Query\Query;

class GetWorkshopOrderByIdQuery implements Query
{


    /**
     * @param int $WorkshopOrderId
     */
    public function __construct(public int $WorkshopOrderId)
    {
    }

}