<?php

declare(strict_types=1);

namespace App\Contremarque\DTO\ImageCommand;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Request;

class GetImageCommandListRequestDto
{
    public function __construct(
        #[Assert\PositiveOrZero]
        public ?int    $designerId = null,

        #[Assert\Positive]
        public ?int    $page = 1,

        #[Assert\Range(min: 1, max: 100)]
        public ?int    $itemsPerPage = 10,
        #[Assert\Type('string')]
        public ?string $customer = null,

        #[Assert\Type('string')]
        public ?string $contremarque = null,

        #[Assert\Type('string')]
        public ?string $commercial = null,

        #[Assert\Type('string')]
        public ?string $command = null,
        #[Assert\Type('string')]
        public ?string $measurementName1 = null,

        #[Assert\Type('string')]
        public ?string $measurementName2 = null,

        #[Assert\Type('numeric')]
        #[Assert\PositiveOrZero]
        public ?float  $minDimensionValue1 = null,

        #[Assert\Type('numeric')]
        #[Assert\PositiveOrZero]
        #[Assert\GreaterThanOrEqual(propertyPath: 'minDimensionValue1')]
        public ?float  $maxDimensionValue1 = null,

        #[Assert\Type('numeric')]
        #[Assert\PositiveOrZero]
        public ?float  $minDimensionValue2 = null,

        #[Assert\Type('numeric')]
        #[Assert\PositiveOrZero]
        #[Assert\GreaterThanOrEqual(propertyPath: 'minDimensionValue2')]
        public ?float  $maxDimensionValue2 = null,
        #[Assert\Positive]
        public ?int    $model = null,
        #[Assert\Positive]
        public ?int    $quality = null,
        #[Assert\Positive]
        public ?int    $collection = null,
        #[Assert\Type('string')]
        public ?string $location = null,

    )
    {
    }
}
