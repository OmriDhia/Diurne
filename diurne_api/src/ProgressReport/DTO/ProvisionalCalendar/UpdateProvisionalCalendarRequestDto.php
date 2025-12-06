<?php

namespace App\ProgressReport\DTO\ProvisionalCalendar;

use App\Common\DTO\BaseDto;

class UpdateProvisionalCalendarRequestDto extends BaseDto
{
    public function __construct(
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

