<?php

namespace App\ProgressReport\Bus\Query\ProgressReport;

use App\Common\Bus\Query\Query;

class GetProgressReportsQuery implements Query
{
    public function __construct(public readonly ?int $provisionalCalendarId = null)
    {
    }
}

