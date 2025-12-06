<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\UpdateDesignerAssignment;

use DateTimeImmutable;
use App\Common\Bus\Command\Command;

final class UpdateDesignerAssignmentCommand implements Command
{
    public ?DateTimeImmutable $dateFrom = null;
    public ?DateTimeImmutable $dateTo = null;

    public function __construct(
        public int $id,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        public ?bool $inProgress = null,
        public ?bool $stopped = null,
        public ?bool $done = null
    ) {
        // Convert strings to DateTimeImmutable, if provided
        if (null !== $dateFrom) {
            $this->dateFrom = new DateTimeImmutable($dateFrom);
        }
        if (null !== $dateTo) {
            $this->dateTo = new DateTimeImmutable($dateTo);
        }
    }
}
