<?php

namespace App\Setting\Bus\Command\ImageType;

use App\Setting\Entity\ImageType;

class UpdateImageTypeResponse
{
    private readonly array $imageTypeData;

    public function __construct(ImageType $imageType)
    {
        $this->imageTypeData = $imageType->toArray();
    }

    public function toArray(): array
    {
        return $this->imageTypeData;
    }
}
