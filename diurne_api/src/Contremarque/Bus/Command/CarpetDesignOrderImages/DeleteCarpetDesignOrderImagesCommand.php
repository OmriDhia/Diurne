<?php

namespace App\Contremarque\Bus\Command\CarpetDesignOrderImages;

use App\Common\Bus\Command\Command;

class DeleteCarpetDesignOrderImagesCommand implements Command
{
    public function __construct(
        private readonly array $imageIds
    )
    {
    }

    public function getImageIds(): array
    {
        return $this->imageIds;
    }
}