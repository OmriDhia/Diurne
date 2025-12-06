<?php

declare(strict_types=1);

namespace App\Setting\Bus\Command\Police;

use App\Common\Bus\Command\CommandResponse;
use App\Setting\Entity\Police;

final readonly class PoliceResponse implements CommandResponse
{
    public function __construct(
        private Police $police
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->police->getId(),
            'label' => $this->police->getLabel(),
        ];
    }
}
