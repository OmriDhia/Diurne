<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\GetDiscountRules;

use App\Common\Bus\Query\QueryHandler;
use App\Setting\Entity\DiscountRule;
use App\Setting\Repository\DiscountRuleRepository;

final readonly class GetDiscountRulesQueryHandler implements QueryHandler
{
    public function __construct(private DiscountRuleRepository $discountRuleRepository)
    {
    }

    public function __invoke(GetDiscountRulesQuery $query)
    {
        $discountRules = $this->discountRuleRepository->findAll();

        $formattedDiscountRules = array_map(fn($discountRule) => [
            'discountRule_id' => $discountRule->getId(),
            'discount' => $discountRule->getDiscount(),
            'title' => $this->getDiscountRuleTitleInLanguage($discountRule, 1),
            'title_lang' => $this->getAllDiscountRuleTitles($discountRule),
        ], $discountRules);

        return new GetDiscountRulesResponse(
            $formattedDiscountRules
        );
    }

    public function getDiscountRuleTitleInLanguage(DiscountRule $discountRule, int $langId): string|null
    {
        foreach ($discountRule->getDiscountRuleLangs() as $lang) {
            if ((int) $lang->getLangId() === $langId) {
                return $lang->getLabel();
            }
        }

        return null; // Handle the case where no translation is found for the specified language
    }

    /**
     * @return (null|string)[][]
     *
     * @psalm-return list{0?: array{id_lang: null, label: null|string},...}
     */
    public function getAllDiscountRuleTitles(DiscountRule $discountRule): array
    {
        $titles = [];
        foreach ($discountRule->getDiscountRuleLangs() as $lang) {
            $titles[] =
                [
                    'id_lang' => $lang->getLangId(),
                    'label' => $lang->getLabel(),
                ];
        }

        return $titles;
    }
}
