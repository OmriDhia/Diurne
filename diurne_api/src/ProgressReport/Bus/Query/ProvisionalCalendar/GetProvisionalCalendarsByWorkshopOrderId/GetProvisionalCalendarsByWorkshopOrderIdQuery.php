<?php

declare(strict_types=1);

namespace App\ProgressReport\Bus\Query\ProvisionalCalendar\GetProvisionalCalendarsByWorkshopOrderId;

use App\Common\Bus\Query\Query;

final readonly class GetProvisionalCalendarsByWorkshopOrderIdQuery implements Query
{
    public function __construct(public int $workshopOrderId)
    {
    }
}
