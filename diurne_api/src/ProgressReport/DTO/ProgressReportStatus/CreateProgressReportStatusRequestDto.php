<?php

namespace App\ProgressReport\DTO\ProgressReportStatus;

use App\Common\DTO\BaseDto;
use Symfony\Component\Validator\Constraints as Assert;

class CreateProgressReportStatusRequestDto extends BaseDto
{
    public function __construct(
        #[Assert\NotBlank]
        public string $status
    ) {
    }
}

