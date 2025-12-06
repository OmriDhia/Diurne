<?php

namespace App\CheckingList\Bus\Command\CreateQualityRespect;

use App\Common\Bus\Command\CommandResponse;
use App\CheckingList\Entity\QualityRespect;

class CreateQualityRespectResponse implements CommandResponse
{
    public function __construct(private readonly QualityRespect $qualityRespect)
    {
    }

    public function toArray(): array
    {
        return $this->qualityRespect->toArray();
    }
}
