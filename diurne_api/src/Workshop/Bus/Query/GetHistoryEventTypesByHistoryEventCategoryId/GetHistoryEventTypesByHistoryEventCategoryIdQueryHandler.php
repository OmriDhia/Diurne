<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Query\GetHistoryEventTypesByHistoryEventCategoryId;

use App\Common\Bus\Query\QueryHandler;
use App\Workshop\Bus\Query\GetHistoryEventTypes\HistoryEventTypesResponse;
use App\Workshop\Repository\HistoryEventCategoryRepository;
use App\Workshop\Repository\HistoryEventTypeRepository;

final class GetHistoryEventTypesByHistoryEventCategoryIdQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly HistoryEventTypeRepository $historyEventTypeRepository,
        private readonly HistoryEventCategoryRepository $historyEventCategoryRepository,
    ) {
    }

    public function __invoke(GetHistoryEventTypesByHistoryEventCategoryIdQuery $query): HistoryEventTypesResponse
    {
        $category = $this->historyEventCategoryRepository->find($query->historyEventCategoryId);
        if (!$category) {
            return new HistoryEventTypesResponse([]);
        }

        $eventTypes = $this->historyEventTypeRepository->findByHistoryEventCategory($category);

        return new HistoryEventTypesResponse($eventTypes);
    }
}
