<?php

namespace App\ProgressReport\Bus\Command\ProgressReportStatus\UpdateProgressReportStatus;

use App\Common\Bus\Command\Command;

class UpdateProgressReportStatusCommand implements Command
{
    public function __construct(public int $id, public ?string $status = null)
    {
    }
}

