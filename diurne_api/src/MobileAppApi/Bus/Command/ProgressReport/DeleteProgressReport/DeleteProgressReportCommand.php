<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Command\ProgressReport\DeleteProgressReport;

use App\Common\Bus\Command\Command;

final class DeleteProgressReportCommand implements Command
{
    public function __construct(
        public readonly int $id
    ) {
    }
}
