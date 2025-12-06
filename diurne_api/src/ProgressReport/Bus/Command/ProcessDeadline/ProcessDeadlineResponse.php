<?php

declare(strict_types=1);

namespace App\ProgressReport\Bus\Command\ProcessDeadline;

use App\Common\Bus\Command\CommandResponse;
use App\ProgressReport\Entity\ProcessDeadline;

class ProcessDeadlineResponse implements CommandResponse
{
    public function __construct(private ProcessDeadline $deadline)
    {
    }

    public function toArray(): array
    {
        return $this->deadline->toArray();
    }
}
