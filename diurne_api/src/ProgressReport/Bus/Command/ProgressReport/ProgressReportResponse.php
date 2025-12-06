<?php

namespace App\ProgressReport\Bus\Command\ProgressReport;

use App\Common\Bus\Command\CommandResponse;
use App\ProgressReport\Entity\ProgressReport;

class ProgressReportResponse implements CommandResponse
{
    public function __construct(private ProgressReport $progressReport)
    {
    }

    public function toArray(): array
    {
        return $this->progressReport->toArray();
    }
}

