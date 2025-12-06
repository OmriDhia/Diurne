<?php

namespace App\ProgressReport\Bus\Command\ProgressReport\CreateProgressReport;

use App\Common\Bus\Command\Command;
use DateTimeInterface;

class CreateProgressReportCommand implements Command
{
    public function __construct(
        public int $authorId,
        public ?DateTimeInterface $datePr = null,
        public ?string $comment = null,
        public ?DateTimeInterface $dateEvent = null,
        public ?DateTimeInterface $dateWorkshop = null,
        public ?string $tissage = null,
        public int $statusId,
        public ?int $provisionalCalendarId = null,
    ) {
    }
}

