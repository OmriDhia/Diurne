<?php

namespace App\ProgressReport\DTO\ProgressReport;

use App\Common\DTO\BaseDto;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateProgressReportRequestDto extends BaseDto
{
    public function __construct(
        public ?int $authorId = null,
        public ?string $datePr = null,
        public ?string $comment = null,
        public ?string $dateEvent = null,
        public ?string $dateWorkshop = null,
        public ?string $tissage = null,
        public ?int $statusId = null,
        public ?int $provisionalCalendarId = null,
    ) {
    }
}

