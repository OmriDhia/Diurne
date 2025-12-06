<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\TransportCondition;

use App\Common\Bus\Query\QueryHandler;
use App\Setting\Repository\TransportConditionRepository;

class GetAllTransportConditionQueryHandler implements QueryHandler
{
    public function __construct(private readonly TransportConditionRepository $transportConditionRepository)
    {
    }

    public function __invoke(GetAllTransportConditionQuery $query): TransportConditionQueryResponse
    {
        $all_transportCondition = $this->transportConditionRepository->findAll();

        return new TransportConditionQueryResponse($all_transportCondition);
    }
}
