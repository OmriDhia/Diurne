<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Command\Workshop\CreateWorkshop;

use App\Common\Bus\Command\CommandResponse;
use App\MobileAppApi\Entity\Workshop;

final class CreateWorkshopResponse implements CommandResponse
{
    public function __construct(
        private readonly Workshop $workshop
    ) {
    }

    public function toArray(): array
    {
        return $this->workshop->toArray();
    }
}
