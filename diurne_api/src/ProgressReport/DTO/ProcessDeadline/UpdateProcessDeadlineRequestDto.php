<?php

declare(strict_types=1);

namespace App\ProgressReport\DTO\ProcessDeadline;

use App\Common\DTO\BaseDto;

class UpdateProcessDeadlineRequestDto extends BaseDto
{
    public function __construct(
        public ?int $processId = null,
        public ?string $dateStart = null,
        public ?string $dateEnd = null,
        public ?string $comment = null,
    ) {
    }
}
