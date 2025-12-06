<?php

declare(strict_types=1);

namespace App\Contremarque\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class GetCarpetDesignOrdersQueryDto
{
    public function __construct(
        public ?string $orderBy,
        public ?string $orderWay,
        #[Assert\LessThanOrEqual(500)]
        public ?int $page = null,
        #[Assert\LessThanOrEqual(100)]
        public ?int $itemsPerPage = null,
        #[Assert\Valid]
        public ?GetCarpetDesignOrdersFilterDto $filter = null,
    ) {
    }
}
