<?php

declare(strict_types=1);

namespace App\Event\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class GetEventsByCustomerQueryDto
{
    public function __construct(
        #[Assert\LessThanOrEqual(500)]
        public ?int $page = null,
        #[Assert\LessThanOrEqual(100)]
        public ?int $itemsPerPage = null,
        #[Assert\Valid]
        public ?GetEventsByCustomerFilterDto $filter = null,
    ) {
    }
}
