<?php

namespace App\Workshop\DTO\MaterialPurchasePrice;

use App\Common\DTO\BaseDto;
use Symfony\Component\Validator\Constraints as Assert;

class CreateMaterialPurchasePriceDto extends BaseDto
{
    /**
     * @param int $materialId
     * @param string $price
     * @param int $productionOrderId
     * @param int $workshopInformationId
     */
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Positive]
        public readonly int    $materialId,

        #[Assert\NotBlank]
        #[Assert\Type(type: 'numeric', message: 'The price must be a number')]
        #[Assert\Positive]
        #[Assert\Regex(
            pattern: '/^\d+(\.\d{1,6})?$/',
            message: 'Price must be a valid decimal with up to 6 decimal places'
        )]
        public readonly string $price,

        #[Assert\NotBlank]
        #[Assert\Positive]
        public readonly int    $productionOrderId,

        #[Assert\Positive]
        public readonly int    $workshopInformationId
    )
    {
    }
}