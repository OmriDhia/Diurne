<?php

declare(strict_types=1);

namespace App\ProgressReport\Bus\Command\ProcessDeadline\UpdateProcessDeadline;

use App\Common\Bus\Command\Command;
use DateTimeInterface;

class UpdateProcessDeadlineCommand implements Command
{
    public function __construct(
        public int $id,
        public ?int $processId = null,
        public ?DateTimeInterface $dateStart = null,
        public ?DateTimeInterface $dateEnd = null,
        public ?string $comment = null,
    ) {
    }
}
