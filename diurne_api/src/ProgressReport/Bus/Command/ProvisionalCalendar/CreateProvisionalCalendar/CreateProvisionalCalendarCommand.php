<?php

namespace App\ProgressReport\Bus\Command\ProvisionalCalendar\CreateProvisionalCalendar;

use App\Common\Bus\Command\Command;
class CreateProvisionalCalendarCommand implements Command
{
    public function __construct(
        public int $workshopOrderId,
        public int $deadlinPreparation,
        public int $deadlinWeave,
        public int $deadlinFinition,
        public ?string $eventPreparation = null,
        public ?string $stopPreparation = null,
        public ?string $eventWeave = null,
        public ?string $stopWeave = null,
        public ?string $eventFinition = null,
        public ?string $stopFinition = null,
    ) {
    }
}

