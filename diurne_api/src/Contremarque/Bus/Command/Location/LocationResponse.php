<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\Location;

use App\Common\Bus\Command\CommandResponse;
use App\Contremarque\Entity\Location;

final class LocationResponse implements CommandResponse
{
    public function __construct(
        public Location $location
    ) {
    }

    public function toArray(): array
    {
        return $this->location->toArray();
    }
}
