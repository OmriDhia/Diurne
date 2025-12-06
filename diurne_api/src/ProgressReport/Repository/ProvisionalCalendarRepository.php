<?php

namespace App\ProgressReport\Repository;

use App\Common\Repository\BaseRepository;
use App\ProgressReport\Entity\ProvisionalCalendar;

interface ProvisionalCalendarRepository extends BaseRepository
{
    public function save(ProvisionalCalendar $calendar, bool $flush = false): void;
}

