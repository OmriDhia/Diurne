<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Command\Workshop\UpdateWorkshop;

use App\Common\Bus\Command\CommandResponse;
use App\MobileAppApi\Entity\Workshop;

final class UpdateWorkshopResponse implements CommandResponse
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
