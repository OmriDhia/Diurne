<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Query\GetWorkshopRnHistoryByWorkshopOrderId;

use App\Common\Bus\Query\Query;

final readonly class GetWorkshopRnHistoryByWorkshopOrderIdQuery implements Query
{
    public function __construct(public int $workshopOrderId)
    {
    }
}
