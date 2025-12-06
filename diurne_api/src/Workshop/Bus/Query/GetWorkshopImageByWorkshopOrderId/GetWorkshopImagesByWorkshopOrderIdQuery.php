<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Query\GetWorkshopImageByWorkshopOrderId;

use App\Common\Bus\Query\Query;

final readonly class GetWorkshopImagesByWorkshopOrderIdQuery implements Query
{
    public function __construct(public int $workshopOrderId)
    {
    }
}
