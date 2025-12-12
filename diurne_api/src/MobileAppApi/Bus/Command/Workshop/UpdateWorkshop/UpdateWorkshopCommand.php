<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Command\Workshop\UpdateWorkshop;

use App\Common\Bus\Command\Command;
use Symfony\Component\Validator\Constraints as Assert;

final class UpdateWorkshopCommand implements Command
{
    public function __construct(
        public readonly int $id,
        #[Assert\NotBlank]
        public readonly string $name,
        public readonly ?string $carpetRnPrefix = null,
        public readonly ?string $sampleRnPrefix = null
    ) {
    }
}
