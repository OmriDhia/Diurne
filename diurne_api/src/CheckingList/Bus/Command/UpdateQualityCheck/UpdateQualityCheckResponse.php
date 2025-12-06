<?php

namespace App\CheckingList\Bus\Command\UpdateQualityCheck;

use App\Common\Bus\Command\CommandResponse;
use App\CheckingList\Entity\QualityCheck;

class UpdateQualityCheckResponse implements CommandResponse
{
    public function __construct(private readonly QualityCheck $qualityCheck)
    {
    }

    public function toArray(): array
    {
        return $this->qualityCheck->toArray();
    }
}
