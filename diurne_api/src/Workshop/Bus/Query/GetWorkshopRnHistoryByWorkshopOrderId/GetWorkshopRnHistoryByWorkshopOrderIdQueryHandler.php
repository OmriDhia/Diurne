<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Query\GetWorkshopRnHistoryByWorkshopOrderId;

use App\Common\Bus\Query\QueryHandler;
use App\Workshop\Bus\Query\GetWorkshopRnHistory\GetWorkshopRnHistoryQueryResponse;
use App\Workshop\Repository\WorkshopOrderRepository;
use App\Workshop\Repository\WorkshopRnHistoryRepository;

final class GetWorkshopRnHistoryByWorkshopOrderIdQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly WorkshopRnHistoryRepository $workshopRnHistoryRepository,
        private readonly WorkshopOrderRepository $workshopOrderRepository,
    ) {
    }

    public function __invoke(GetWorkshopRnHistoryByWorkshopOrderIdQuery $query): GetWorkshopRnHistoryQueryResponse
    {
        $workshopOrder = $this->workshopOrderRepository->find($query->workshopOrderId);
        if (!$workshopOrder) {
            return new GetWorkshopRnHistoryQueryResponse([]);
        }

        $histories = $this->workshopRnHistoryRepository->findBy(['workshopOrder' => $workshopOrder]);

        return new GetWorkshopRnHistoryQueryResponse($histories);
    }
}
