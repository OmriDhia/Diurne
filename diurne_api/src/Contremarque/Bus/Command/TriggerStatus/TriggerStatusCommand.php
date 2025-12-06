<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\TriggerStatus;

use App\Common\Bus\Command\Command;

class TriggerStatusCommand implements Command
{
    public function __construct(
        public readonly int $quoteDetailId,
        public readonly bool $newStatus
    ) {
    }
}
