<?php

namespace App\Contremarque\Bus\Query\ImageCommand;

use App\Common\Bus\Query\Query;

final readonly class GetImageCommandQuery implements Query
{

    public function __construct(
        public ?int    $designerId = null,
        public ?int    $page = 1,
        public ?int    $itemsPerPage = 25,
        // Add new string filter fields
        public ?string $customer = null,
        public ?string $contremarque = null,
        public ?string $commercial = null,
        public ?string $command = null,
        public ?string $status = null,
        public ?string $measurementName1 = null,
        public ?string $measurementName2 = null,
        public ?float  $minDimensionValue1 = null,
        public ?float  $maxDimensionValue1 = null,
        public ?float  $minDimensionValue2 = null,
        public ?float  $maxDimensionValue2 = null,
        public ?int    $model = null,
        public ?int    $collection = null,
        public ?int    $quality = null,
        public ?string $location = null,
        public ?string $orderBy = null,
        public ?string $orderWay = 'DESC'
    )
    {
    }
}