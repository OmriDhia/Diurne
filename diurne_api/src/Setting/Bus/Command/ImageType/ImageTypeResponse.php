<?php

namespace App\Setting\Bus\Command\ImageType;

use App\Common\Bus\Command\CommandResponse;
use App\Setting\Entity\ImageType;

class ImageTypeResponse implements CommandResponse
{
    public function __construct(private readonly ImageType $imageType)
    {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->imageType->getId(),
            'name' => $this->imageType->getName(),
            'description' => $this->imageType->getDescription(),
            'category' => $this->imageType->getCategory(),
        ];
    }
}
