<?php

namespace App\ProgressReport\Bus\Command\ProvisionalCalendar;

use App\Common\Bus\Command\CommandResponse;
use App\ProgressReport\Entity\ProvisionalCalendar;

class ProvisionalCalendarResponse implements CommandResponse
{
    public function __construct(private ProvisionalCalendar $calendar)
    {
    }

    public function toArray(): array
    {
        return $this->calendar->toArray();
    }
}

