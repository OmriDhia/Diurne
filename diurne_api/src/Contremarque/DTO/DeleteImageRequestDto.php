<?php

namespace App\Contremarque\DTO;

use Symfony\Component\Serializer\Attribute\SerializedName;

class DeleteImageRequestDto
{
    public function __construct(
        /**
         * @var int[]
         * @SerializedName("imageIds")
         */
        private readonly array $imageIds
    )
    {
    }

    public function getImageIds(): array
    {
        return $this->imageIds;
    }
}