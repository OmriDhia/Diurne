<?php

namespace App\Contremarque\DTO;

class UpdateImageTypeRequest
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