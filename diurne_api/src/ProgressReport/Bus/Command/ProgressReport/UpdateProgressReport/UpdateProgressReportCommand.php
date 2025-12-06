<?php

namespace App\ProgressReport\Bus\Command\ProgressReport\UpdateProgressReport;

use App\Common\Bus\Command\Command;
use DateTimeInterface;

class UpdateProgressReportCommand implements Command
{
    public function __construct(
        public int $id,
        public ?int $authorId = null,
        public ?DateTimeInterface $datePr = null,
        public ?string $comment = null,
        public ?DateTimeInterface $dateEvent = null,
        public ?DateTimeInterface $dateWorkshop = null,
        public ?string $tissage = null,
        public ?int $statusId = null,
        public ?int $provisionalCalendarId = null,
    ) {
    }
}

