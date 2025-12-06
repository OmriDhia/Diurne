<?php

declare(strict_types=1);

namespace App\Contremarque\DTO;

class UpdateDesignerAssignmentRequestDto
{
    public function __construct(
        public ?string $dateFrom = null,
        public ?string $dateTo = null,
        public ?bool $inProgress = null,
        public ?bool $stopped = null,
        public ?bool $done = null
    ) {
    }
}
