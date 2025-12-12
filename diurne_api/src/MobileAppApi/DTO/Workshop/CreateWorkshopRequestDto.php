<?php

declare(strict_types=1);

namespace App\MobileAppApi\DTO\Workshop;

final class CreateWorkshopRequestDto
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $carpetRnPrefix = null,
        public readonly ?string $sampleRnPrefix = null
    ) {
    }
}
