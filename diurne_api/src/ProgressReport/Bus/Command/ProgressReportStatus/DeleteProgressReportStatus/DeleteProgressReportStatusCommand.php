<?php

namespace App\ProgressReport\Bus\Command\ProgressReportStatus\DeleteProgressReportStatus;

use App\Common\Bus\Command\Command;

class DeleteProgressReportStatusCommand implements Command
{
    public function __construct(public int $id)
    {
    }
}

