<?php

namespace App\Setting\Bus\Command\Quality;

use App\Common\Bus\Command\CommandResponse;
use App\Setting\Entity\Quality;

class QualityResponse implements CommandResponse
{
    public function __construct(private readonly Quality $quality) {}

    public function toArray(): array
    {
        return $this->quality->toArray();
    }

    public function getQuality(): Quality
    {
        return $this->quality;
    }
}
