<?php

namespace App\Contremarque\Bus\Command\Location;

use App\Common\Bus\Command\Command;

class DeleteLocationCommand implements Command
{
    public function __construct(
        private readonly int $locationId
    ) {}

    public function getLocationId(): int
    {
        return $this->locationId;
    }
}
