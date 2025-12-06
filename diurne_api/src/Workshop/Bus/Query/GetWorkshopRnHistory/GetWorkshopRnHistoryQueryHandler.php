<?php

namespace App\Workshop\Bus\Query\GetWorkshopRnHistory;

use App\Common\Bus\Query\QueryHandler;
use App\Workshop\Repository\WorkshopRnHistoryRepository;

class GetWorkshopRnHistoryQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly WorkshopRnHistoryRepository $repository
    )
    {
    }

    public function __invoke(GetWorkshopRnHistoryQuery $query): GetWorkshopRnHistoryQueryResponse
    {
        $workshopRnHistory = $this->repository->findAll();
        return new GetWorkshopRnHistoryQueryResponse($workshopRnHistory);
    }

}