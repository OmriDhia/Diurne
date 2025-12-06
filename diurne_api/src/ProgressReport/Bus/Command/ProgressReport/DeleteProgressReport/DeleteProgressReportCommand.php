<?php

namespace App\ProgressReport\Bus\Command\ProgressReport\DeleteProgressReport;

use App\Common\Bus\Command\Command;

class DeleteProgressReportCommand implements Command
{
    public function __construct(public int $id)
    {
    }
}

