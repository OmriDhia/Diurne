<?php

declare(strict_types=1);

namespace App\MobileAppApi\DTO\ProgressReport;

final class UpdateProgressReportRequestDto
{
    public function __construct(
        public readonly string $state,
        public readonly bool $isWoven = false,
        public readonly ?string $comment = null,
        public readonly ?int $userId = null
    ) {
    }
}
