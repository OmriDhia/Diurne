<?php

declare(strict_types=1);

namespace App\Event\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class GetEventsQueryDto
{
    public function __construct(
        public ?string $orderBy,
        public ?string $orderWay,
        #[Assert\LessThanOrEqual(500)]
        public ?int $page = null,
        #[Assert\LessThanOrEqual(100)]
        public ?int $itemsPerPage = null,
        #[Assert\Valid]
        public ?GetEventsFilterDto $filter = null,
    ) {
    }
}
