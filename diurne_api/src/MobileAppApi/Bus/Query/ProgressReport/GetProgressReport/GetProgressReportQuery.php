<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Query\ProgressReport\GetProgressReport;

use App\Common\Bus\Query\Query;

final class GetProgressReportQuery implements Query
{
    public function __construct(
        public readonly int $id
    ) {
    }
}
