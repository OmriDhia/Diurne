<?php

declare(strict_types=1);

namespace App\Contact\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class GetAgentsQueryDto
{
    public function __construct(
        #[Assert\LessThanOrEqual(500)]
        public ?int $page = null,
        #[Assert\LessThanOrEqual(100)]
        public ?int $itemPerPage = null,
        public ?string $orderBy = null,
        public ?string $orderWay = null,
        #[Assert\Valid]
        public ?GetAgentsFilterDto $filter = null,
    ) {
    }
}
