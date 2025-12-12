<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Command\RN\CreateRN;

use App\Common\Bus\Command\Command;
use Symfony\Component\Validator\Constraints as Assert;

final class CreateRNCommand implements Command
{
    public function __construct(
        #[Assert\NotBlank]
        public readonly string $rnNumber,
        public readonly ?int $workshopId = null
    ) {
    }
}
