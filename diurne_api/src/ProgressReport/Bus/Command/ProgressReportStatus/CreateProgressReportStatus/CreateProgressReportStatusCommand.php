<?php

namespace App\ProgressReport\Bus\Command\ProgressReportStatus\CreateProgressReportStatus;

use App\Common\Bus\Command\Command;

class CreateProgressReportStatusCommand implements Command
{
    public function __construct(public string $status)
    {
    }
}

