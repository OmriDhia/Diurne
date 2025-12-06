<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\GetAllTaxRule;

use App\Common\Bus\Query\QueryHandler;
use App\Setting\Entity\TaxRule;
use App\Setting\Repository\TaxRuleRepository;
use Doctrine\ORM\EntityManagerInterface;

class GetAllTaxRulesQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly TaxRuleRepository $taxRuleRepository
    ) {
    }

    /**
     * Handle the GetAllTaxRulesQuery and return an array of TaxRule entities.
     */
    public function __invoke(GetAllTaxRulesQuery $query): GetAllTaxRuleResponse
    {
        $repository = $this->taxRuleRepository;
        $taxRules = $repository->findAll();

        return new GetAllTaxRuleResponse($taxRules);
    }
}
