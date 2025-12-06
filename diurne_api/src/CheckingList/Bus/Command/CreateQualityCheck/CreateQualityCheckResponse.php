<?php

namespace App\CheckingList\Bus\Command\CreateQualityCheck;

use App\Common\Bus\Command\CommandResponse;
use App\CheckingList\Entity\QualityCheck;

class CreateQualityCheckResponse implements CommandResponse
{
    public function __construct(private readonly QualityCheck $qualityCheck)
    {
    }

    public function toArray(): array
    {
        return $this->qualityCheck->toArray();
    }
}
