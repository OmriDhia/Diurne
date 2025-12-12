<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Command\RN\UpdateRN;

use App\Common\Bus\Command\Command;
use Symfony\Component\Validator\Constraints as Assert;

final class UpdateRNCommand implements Command
{
    public function __construct(
        public readonly int $id,
        #[Assert\NotBlank]
        public readonly string $rnNumber,
        public readonly ?int $workshopId = null
    ) {
    }
}
