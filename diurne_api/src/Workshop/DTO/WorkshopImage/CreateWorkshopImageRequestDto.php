<?php

namespace App\Workshop\DTO\WorkshopImage;

use App\Common\DTO\BaseDto;
use App\Workshop\Entity\WorkshopImage;
use Symfony\Component\Validator\Constraints as Assert;

class CreateWorkshopImageRequestDto extends BaseDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(max: 50)]
        #[Assert\UniqueEntity(fields: ['file_name'], entityClass: WorkshopImage::class)]
        public readonly string  $fileName,

        #[Assert\NotBlank]
        #[Assert\Positive]
        public readonly int     $idImageType,

        #[Assert\NotBlank]
        #[Assert\Length(max: 50)]
        public readonly string  $format,

        #[Assert\NotBlank]
        #[Assert\Positive]
        public readonly int     $locationId,

        #[Assert\NotBlank]
        #[Assert\Positive]
        public readonly int     $workshopOrderId,

        /*        #[Assert\NotBlank]
                #[Assert\Positive]
                public readonly int     $attachmentId,*/

        #[Assert\DateTime(format: 'Y-m-d H:i:s')]
        public readonly ?string $createdAt = null,

        #[Assert\DateTime(format: 'Y-m-d H:i:s')]
        public readonly ?string $updatedAt = null
    )
    {
    }
}