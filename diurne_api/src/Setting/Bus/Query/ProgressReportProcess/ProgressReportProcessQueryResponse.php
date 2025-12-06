<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\ProgressReportProcess;

use App\Common\Bus\Query\QueryResponse;
use App\Setting\Entity\ProgressReportProcess;

class ProgressReportProcessQueryResponse implements QueryResponse
{
    /** @param ProgressReportProcess[] $processes */
    public function __construct(private array $processes)
    {
    }

    public function toArray(): array
    {
        return array_map(fn(ProgressReportProcess $p) => $p->toArray(), $this->processes);
    }
}
