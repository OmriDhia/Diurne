<?php

declare(strict_types=1);

namespace App\Setting\DTO;

class UpdateTaxRuleRequestDto
{
    public function __construct(
        public ?string $taxRate,
        public ?array $identifications
    ) {
    }
}
