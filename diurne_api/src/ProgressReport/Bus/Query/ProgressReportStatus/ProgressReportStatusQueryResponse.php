<?php

namespace App\ProgressReport\Bus\Query\ProgressReportStatus;

use App\Common\Bus\Query\QueryResponse;
use App\ProgressReport\Entity\ProgressReportStatus;

class ProgressReportStatusQueryResponse implements QueryResponse
{
    public function __construct(private array $statuses)
    {
    }

    public function toArray(): array
    {
        return array_map(fn(ProgressReportStatus $s) => $s->toArray(), $this->statuses);
    }
}

