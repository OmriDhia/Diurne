<?php

namespace App\Setting\Bus\Command\TaxRule;

use App\Common\Bus\Command\Command;

class CreateTaxRuleCommand implements Command
{
    public function __construct(
        public string $taxRate,
        public array $identifications
    ) {
    }
}
