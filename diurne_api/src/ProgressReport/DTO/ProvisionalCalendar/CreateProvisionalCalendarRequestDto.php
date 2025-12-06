<?php

namespace App\ProgressReport\DTO\ProvisionalCalendar;

use App\Common\DTO\BaseDto;
use Symfony\Component\Validator\Constraints as Assert;

class CreateProvisionalCalendarRequestDto extends BaseDto
{
    public function __construct(
        #[Assert\Positive] public int $workshopOrderId,
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

