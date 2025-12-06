<?php

namespace App\Setting\Bus\Command\TaxRule;

use App\Setting\Entity\TaxRule;

class TaxRuleResponse
{
    public function __construct(
        public int $id,
        public string $taxRate,
        public array $identifications
    ) {
    }

    public static function fromEntity(TaxRule $taxRule): self
    {
        $identifications = [];
        foreach ($taxRule->getTaxRuleLangs() as $lang) {
            $identifications[] = [
                'language_id' => $lang->getLanguage()->getId(),
                'identification' => $lang->getIdentification(),
            ];
        }

        return new self(
            $taxRule->getId(),
            $taxRule->getTaxRate(),
            $identifications
        );
    }
}
