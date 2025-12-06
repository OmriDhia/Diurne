<?php

declare(strict_types=1);

namespace App\ProgressReport\DTO\ProcessDeadline;

use App\Common\DTO\BaseDto;
use Symfony\Component\Validator\Constraints as Assert;

class CreateProcessDeadlineRequestDto extends BaseDto
{
    public function __construct(
        #[Assert\Positive] public int $progressReportId,
        #[Assert\Positive] public int $processId,
        public ?string $dateStart = null,
        public ?string $dateEnd = null,
        public ?string $comment = null,
    ) {
    }
}
