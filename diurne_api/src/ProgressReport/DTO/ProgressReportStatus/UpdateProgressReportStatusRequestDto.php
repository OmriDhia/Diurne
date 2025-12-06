<?php

namespace App\ProgressReport\DTO\ProgressReportStatus;

use App\Common\DTO\BaseDto;

class UpdateProgressReportStatusRequestDto extends BaseDto
{
    public function __construct(
        public ?string $status = null
    ) {
    }
}

