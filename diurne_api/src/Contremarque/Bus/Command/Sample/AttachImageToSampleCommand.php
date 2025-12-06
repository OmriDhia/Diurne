<?php

namespace App\Contremarque\Bus\Command\Sample;

use App\Common\Bus\Command\Command;

class AttachImageToSampleCommand implements Command
{
    public function __construct(private readonly int $sampleId, private readonly int $imageId)
    {
    }

    public function getSampleId(): int
    {
        return $this->sampleId;
    }

    public function getImageId(): int
    {
        return $this->imageId;
    }
}