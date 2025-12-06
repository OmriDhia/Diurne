<?php

declare(strict_types=1);

namespace App\ProgressReport\Bus\Command\ProcessDeadline\CreateProcessDeadline;

use App\Common\Bus\Command\Command;
use DateTimeInterface;

class CreateProcessDeadlineCommand implements Command
{
    public function __construct(
        public int $progressReportId,
        public int $processId,
        public ?DateTimeInterface $dateStart = null,
        public ?DateTimeInterface $dateEnd = null,
        public ?string $comment = null,
    ) {
    }
}
