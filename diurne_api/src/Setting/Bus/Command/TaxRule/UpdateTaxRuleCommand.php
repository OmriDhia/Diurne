<?php

namespace App\Setting\Bus\Command\TaxRule;

use App\Common\Bus\Command\Command;

class UpdateTaxRuleCommand implements Command
{
    public function __construct(
        public int $id,
        public ?string $taxRate,
        public ?array $identifications
    ) {
    }
}
