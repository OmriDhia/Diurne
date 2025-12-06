<?php

namespace App\ProgressReport\DTO\ProgressReport;

use App\Common\DTO\BaseDto;
use Symfony\Component\Validator\Constraints as Assert;

class CreateProgressReportRequestDto extends BaseDto
{
    public function __construct(
        #[Assert\Positive] public int $authorId,
        public ?string $datePr = null,
        public ?string $comment = null,
        public ?string $dateEvent = null,
        public ?string $dateWorkshop = null,
        public ?string $tissage = null,
        #[Assert\Positive] public int $statusId,
        public ?int $provisionalCalendarId = null,
    ) {
    }
}

