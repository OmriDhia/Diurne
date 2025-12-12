<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Command\ProgressReport\UpdateProgressReport;

use App\Common\Bus\Command\Command;
use Symfony\Component\Validator\Constraints as Assert;

final class UpdateProgressReportCommand implements Command
{
    public function __construct(
        public readonly int $id,
        #[Assert\NotBlank]
        public readonly string $state,
        public readonly bool $isWoven = false,
        public readonly ?string $comment = null,
        public readonly ?int $userId = null
    ) {
    }
}
