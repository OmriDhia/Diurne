<?php

declare(strict_types=1);

namespace App\MobileAppApi\DTO\RN;

final class CreateRNRequestDto
{
    public function __construct(
        public readonly string $rnNumber,
        public readonly ?int $workshopId = null
    ) {
    }
}
