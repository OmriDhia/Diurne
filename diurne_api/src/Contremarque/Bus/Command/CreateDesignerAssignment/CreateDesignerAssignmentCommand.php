<?php

namespace App\Contremarque\Bus\Command\CreateDesignerAssignment;

use DateTimeImmutable;
use App\Common\Bus\Command\Command;

final class CreateDesignerAssignmentCommand implements Command
{
    public ?DateTimeImmutable $dateFrom = null;
    public ?DateTimeImmutable $dateTo = null;

    public function __construct(
        public int  $carpetDesignOrderId,
        public int  $designerId,
        public bool $inProgress,
        public bool $stopped,
        public bool $done,
        ?string     $dateTo = null
    )
    {
        if ($dateTo !== null && strtotime($dateTo) !== false) {
            $this->dateTo = new DateTimeImmutable($dateTo);
        }
    }
}
