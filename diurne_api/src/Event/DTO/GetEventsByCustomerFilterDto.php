<?php

declare(strict_types=1);

namespace App\Event\DTO;

class GetEventsByCustomerFilterDto
{
    public function __construct(
        public ?int $contremarqueId = 0,
    ) {
    }
}
