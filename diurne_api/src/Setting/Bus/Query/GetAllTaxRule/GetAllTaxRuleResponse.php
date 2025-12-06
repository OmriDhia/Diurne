<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\GetAllTaxRule;

use App\Common\Bus\Query\QueryResponse;
use App\Setting\Entity\TaxRule;

class GetAllTaxRuleResponse implements QueryResponse
{
    public function __construct(private $taxRules)
    {
    }

    /**
     * @return array[]
     *
     * @psalm-return array<array>
     */
    public function toArray(): array
    {
        return array_map(fn (TaxRule $taxRule) => TaxRuleResponse::fromEntity($taxRule)->toArray(), $this->taxRules);
    }
}
