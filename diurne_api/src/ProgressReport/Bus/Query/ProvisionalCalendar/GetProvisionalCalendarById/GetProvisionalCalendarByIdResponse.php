<?php

namespace App\ProgressReport\Bus\Query\ProvisionalCalendar\GetProvisionalCalendarById;

use App\Common\Bus\Query\QueryResponse;
use App\ProgressReport\Entity\ProvisionalCalendar;

class GetProvisionalCalendarByIdResponse implements QueryResponse
{
    public function __construct(private ProvisionalCalendar $calendar)
    {
    }

    public function toArray(): array
    {
        return $this->calendar->toArray();
    }
}
