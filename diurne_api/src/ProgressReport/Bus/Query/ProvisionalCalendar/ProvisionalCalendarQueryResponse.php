<?php

namespace App\ProgressReport\Bus\Query\ProvisionalCalendar;

use App\Common\Bus\Query\QueryResponse;
use App\ProgressReport\Entity\ProvisionalCalendar;

class ProvisionalCalendarQueryResponse implements QueryResponse
{
    public function __construct(private array $calendars)
    {
    }

    public function toArray(): array
    {
        return array_map(fn(ProvisionalCalendar $c) => $c->toArray(), $this->calendars);
    }
}

