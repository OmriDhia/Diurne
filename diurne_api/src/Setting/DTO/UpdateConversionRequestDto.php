<?php

namespace App\Setting\DTO;

use DateTimeImmutable;

class UpdateConversionRequestDto
{
    public ?int $currencyId = null;
    public ?DateTimeImmutable $conversionDate = null;
    public ?string $coefficient = null;
}
