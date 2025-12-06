<?php

namespace App\Setting\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateManufacturerPriceGridRequestDto
{
    public function __construct(
        #[Assert\Positive]
        public ?int    $manufacturerId = null,

        #[Assert\Positive]
        public ?int    $tarifGroupId = null,

        public ?string $tariffGrid = null,

        #[Assert\PositiveOrZero]
        public ?int    $knots = null,

        public ?string $special = null,

        #[Assert\PositiveOrZero]
        public ?string $standardVelours = null,

        public ?bool   $isActive = null,

        // Optional prices array: [ { materialId: int, price: string|float, effectiveDate: string } , ... ]
        public ?array  $prices = null
    )
    {
    }
}
