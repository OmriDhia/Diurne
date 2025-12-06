<?php

namespace App\ProgressReport\Bus\Command\ProvisionalCalendar\DeleteProvisionalCalendar;

use App\Common\Bus\Command\Command;

class DeleteProvisionalCalendarCommand implements Command
{
    public function __construct(public int $id)
    {
    }
}

