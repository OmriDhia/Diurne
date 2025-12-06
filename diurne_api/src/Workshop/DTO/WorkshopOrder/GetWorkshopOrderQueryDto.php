<?php

namespace App\Workshop\DTO\WorkshopOrder;

use Symfony\Component\Validator\Constraints as Assert;

class GetWorkshopOrderQueryDto
{
    public function __construct(
        #[Assert\Positive]
        public ?int    $page = 1,

        #[Assert\Positive]
        #[Assert\Range(min: 1, max: 100)]
        public ?int    $itemsPerPage = 10,

        public ?array  $filters = null,

        public ?array  $orderBy = null,
        public ?string $customer = null,
        public ?string $contremarque = null,
        public ?string $reference = null,
        public ?string $commercial = null,
        public ?string $collection = null,
        public ?string $model = null,
        public ?string $rn = null,
        public ?string $location = null,
    )
    {
    }
}