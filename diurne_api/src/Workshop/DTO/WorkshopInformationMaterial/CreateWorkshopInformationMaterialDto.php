<?php

namespace App\Workshop\DTO\WorkshopInformationMaterial;

use App\Common\DTO\BaseDto;
use Symfony\Component\Validator\Constraints as Assert;

class CreateWorkshopInformationMaterialDto extends BaseDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Positive]
        public readonly int     $materialId,

        #[Assert\NotBlank]
        #[Assert\Type(type: 'numeric', message: 'The rate must be a number')]
        #[Assert\Regex(
            pattern: '/^\d+(\.\d{1,6})?$/',
            message: 'Rate must be a valid decimal with up to 6 decimal places'
        )]
        public readonly string  $rate,

        #[Assert\Type(type: 'numeric', message: 'The price must be a number')]
        #[Assert\Regex(
            pattern: '/^\d+(\.\d{1,6})?$/',
            message: 'Price must be a valid decimal with up to 6 decimal places'
        )]
        public readonly ?string $price = null,

        #[Assert\NotBlank]
        #[Assert\Positive]
        public readonly int     $workshopInformationId
    )
    {
    }
}
