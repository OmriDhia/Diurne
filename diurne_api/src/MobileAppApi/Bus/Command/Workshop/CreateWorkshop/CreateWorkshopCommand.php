<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Command\Workshop\CreateWorkshop;

use App\Common\Bus\Command\Command;
use Symfony\Component\Validator\Constraints as Assert;

final class CreateWorkshopCommand implements Command
{
    public function __construct(
        #[Assert\NotBlank]
        public readonly string $name,
        public readonly ?string $carpetRnPrefix = null,
        public readonly ?string $sampleRnPrefix = null
    ) {
    }
}
