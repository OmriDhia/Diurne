<?php

declare(strict_types=1);

namespace App\Setting\Bus\Command\TarifGroup;

use App\Common\Bus\Command\CommandResponse;
use App\Setting\Entity\TarifGroup;

class TarifGroupResponse implements CommandResponse
{
    public function __construct(
        public TarifGroup $tarifGroup
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->tarifGroup->getId(),
            'year' => $this->tarifGroup->getYear(),
        ];
    }
}
