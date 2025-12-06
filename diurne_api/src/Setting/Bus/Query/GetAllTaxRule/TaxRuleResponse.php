<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\GetAllTaxRule;

use App\Common\Bus\Query\QueryResponse;
use App\Setting\Entity\TaxRule;

class TaxRuleResponse implements QueryResponse
{
    public function __construct(
        private readonly int $id,
        private readonly string $taxRate,
        private readonly string $rate,
        private readonly array $identifications // This will contain related TaxRuleLang data
    ) {
    }

    public static function fromEntity(TaxRule $taxRule): self
    {
        return new self(
            $taxRule->getId(),
            $taxRule->getTaxRate(),
            $taxRule->getRate(),
            $taxRule->getTaxRuleLangs()->map(fn ($lang) => [
                'language_id' => $lang->getLanguage()->getId(),
                'identification' => $lang->getIdentification(),
            ])->toArray()
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'taxRate' => $this->taxRate,
            'rate' => $this->rate,
            'identifications' => $this->identifications,
        ];
    }
}
