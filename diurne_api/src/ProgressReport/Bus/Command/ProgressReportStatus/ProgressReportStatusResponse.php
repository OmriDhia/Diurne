<?php

namespace App\ProgressReport\Bus\Command\ProgressReportStatus;

use App\Common\Bus\Command\CommandResponse;
use App\ProgressReport\Entity\ProgressReportStatus;

class ProgressReportStatusResponse implements CommandResponse
{
    public function __construct(private ProgressReportStatus $status)
    {
    }

    public function toArray(): array
    {
        return $this->status->toArray();
    }
}

