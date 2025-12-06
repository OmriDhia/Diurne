<?php

namespace App\Contremarque\DTO;

class DeleteAttachmentsRequestDto
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