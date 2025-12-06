<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\Tarif;

use RuntimeException;
use App\Common\Bus\Query\QueryHandler;
use App\Setting\Entity\DiscountRule;
use App\Setting\Repository\DiscountRuleRepository;
use App\Setting\Repository\TarifRepository;

class GetAllTarifQueryHandler implements QueryHandler
{
    public function __construct(private readonly TarifRepository $tarifRepository, private readonly DiscountRuleRepository $discountRuleRepository) {}

    public function __invoke(GetAllTarifQuery $query): TarifQueryResponse
    {
        $dto = $query->getDto();
        $discountRuleId = $dto->discountRuleId;
        $discountRule = $this->discountRuleRepository->find((int)$discountRuleId);

        if (!$discountRule instanceof DiscountRule && !empty($discountRuleId)) {
            throw new RuntimeException('Discount Rule not found');
        }


        if (!empty($discountRuleId)) {
            $tarifs = $this->tarifRepository->findBy(
                ['discountRule' => $discountRule],
                ['id' => 'DESC'] // Order by id in descending order
            );
        } else {
            $tarifs = $this->tarifRepository->findBy(
                [],
                ['id' => 'DESC'] // Order by id in descending order
            );
        }

        return new TarifQueryResponse($tarifs);
    }
}
