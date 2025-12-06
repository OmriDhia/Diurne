<?php

namespace App\Setting\DTO;

use DateTimeImmutable;

class CreateConversionRequestDto
{
    public int $currencyId;
    public ?DateTimeImmutable $conversionDate = null;
    public string $coefficient;
}
