<?php

namespace App\ProgressReport\Bus\Query\ProgressReport;

use App\Common\Bus\Query\QueryResponse;
use App\ProgressReport\Entity\ProgressReport;

class ProgressReportQueryResponse implements QueryResponse
{
    public function __construct(private array $reports)
    {
    }

    public function toArray(): array
    {
        return array_map(fn(ProgressReport $r) => $r->toArray(), $this->reports);
    }
}

