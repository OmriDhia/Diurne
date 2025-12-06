<?php

namespace App\CheckingList\Bus\Command\UpdateQualityRespect;

use App\Common\Bus\Command\CommandResponse;
use App\CheckingList\Entity\QualityRespect;

class UpdateQualityRespectResponse implements CommandResponse
{
    public function __construct(private readonly QualityRespect $qualityRespect)
    {
    }

    public function toArray(): array
    {
        return $this->qualityRespect->toArray();
    }
}
