<?php

namespace App\ProgressReport\Bus\Command\ProvisionalCalendar\UpdateProvisionalCalendar;

use App\Common\Bus\Command\Command;
class UpdateProvisionalCalendarCommand implements Command
{
    public function __construct(
        public int $id,
        public ?int $workshopOrderId = null,
        public ?int $deadlinPreparation = null,
        public ?int $deadlinWeave = null,
        public ?int $deadlinFinition = null,
        public ?string $eventPreparation = null,
        public ?string $stopPreparation = null,
        public ?string $eventWeave = null,
        public ?string $stopWeave = null,
        public ?string $eventFinition = null,
        public ?string $stopFinition = null,
    ) {
    }
}

