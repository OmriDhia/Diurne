<?php

namespace App\Contremarque\Bus\Command\TechnicalImage;

use App\Common\Bus\Command\CommandResponse;
use App\Contremarque\Entity\ImageCommand\TechnicalImage;

class TechnicalImageResponse implements CommandResponse
{
    public function __construct(private readonly TechnicalImage $technicalImage)
    {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->technicalImage->getId(),
            'image_command' => $this->technicalImage->getImageCommand()->getId(),
            'image_type' => $this->technicalImage->getImageType()->getId(),
            'name' => $this->technicalImage->getName(),
            'attachment' => $this->technicalImage->getAttachment()->getId(),
        ];
    }
}
