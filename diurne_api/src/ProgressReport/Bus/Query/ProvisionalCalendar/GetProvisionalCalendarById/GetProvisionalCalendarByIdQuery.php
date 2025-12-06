<?php

namespace App\ProgressReport\Bus\Query\ProvisionalCalendar\GetProvisionalCalendarById;

use App\Common\Bus\Query\Query;

class GetProvisionalCalendarByIdQuery implements Query
{
    public function __construct(public int $id)
    {
    }
}
