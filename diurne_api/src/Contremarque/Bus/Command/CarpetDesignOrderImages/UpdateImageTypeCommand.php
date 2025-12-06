<?php

namespace App\Contremarque\Bus\Command\CarpetDesignOrderImages;

use App\Common\Bus\Command\Command;

class UpdateImageTypeCommand implements Command
{
    public function __construct(private readonly int $imageId, private readonly int $imageTypeId)
    {
    }

    public function getImageId(): int
    {
        return $this->imageId;
    }

    public function getImageTypeId(): int
    {
        return $this->imageTypeId;
    }
}