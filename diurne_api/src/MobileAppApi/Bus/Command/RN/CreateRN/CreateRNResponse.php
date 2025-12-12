<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Command\RN\CreateRN;

use App\Common\Bus\Command\CommandResponse;
use App\MobileAppApi\Entity\RN;

final class CreateRNResponse implements CommandResponse
{
    public function __construct(
        private readonly RN $rn
    ) {
    }

    public function toArray(): array
    {
        return $this->rn->toArray();
    }
}
