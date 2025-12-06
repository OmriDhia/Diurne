<?php

namespace App\Workshop\DTO\WorkshopImage;

use App\Common\DTO\BaseDto;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateWorkshopImageRequestDto
{
    public function __construct(
        #[Assert\Length(max: 50)]
        public readonly ?string $fileName = null,

        #[Assert\Positive]
        public readonly ?int    $idImageType = null,

        #[Assert\Length(max: 50)]
        public readonly ?string $format = null,

        #[Assert\Positive]
        public readonly ?int    $locationId = null,

        #[Assert\Positive]
        public readonly ?int    $workshopOrderId = null,

        #[Assert\Positive]
        public readonly ?int    $attachmentId = null,

    )
    {
    }
}