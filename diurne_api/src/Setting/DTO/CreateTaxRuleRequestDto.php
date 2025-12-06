<?php

declare(strict_types=1);

namespace App\Setting\DTO;

class CreateTaxRuleRequestDto
{
    public function __construct(
        public string $taxRate,
        public array $identifications
    ) {
    }
}
