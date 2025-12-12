<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Command\ProgressReport\UpdateProgressReport;

use App\Common\Bus\Command\CommandResponse;
use App\MobileAppApi\Entity\ProgressReport;

final class UpdateProgressReportResponse implements CommandResponse
{
    public function __construct(
        private readonly ProgressReport $progressReport
    ) {
    }

    public function toArray(): array
    {
        return $this->progressReport->toArray();
    }
}
