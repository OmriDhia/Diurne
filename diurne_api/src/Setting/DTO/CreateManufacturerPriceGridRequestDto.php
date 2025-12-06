<?php

namespace App\Setting\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreateManufacturerPriceGridRequestDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Positive]
        public int     $manufacturerId,

        #[Assert\NotBlank]
        #[Assert\Positive]
        public int     $qualityId,

        #[Assert\NotBlank]
        #[Assert\Positive]
        public int     $tarifGroupId,

        public ?string $tariffGrid = null,

        #[Assert\PositiveOrZero]
        public ?int    $knots = null,

        public ?string $special = null,

        #[Assert\PositiveOrZero]
        public ?string $standardVelours = null,

        public ?bool   $isActive = true,

        // Prices array: each item is expected to be an object with materialId, price and effectiveDate
        public ?array  $prices = null
    )
    {
    }
}
