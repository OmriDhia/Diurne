<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Query\GetHistoryEventCategory;

use App\Common\Bus\Query\QueryHandler;
use App\Workshop\Repository\HistoryEventCategoryRepository;

class GetHistoryEventCategoryQueryHandler implements QueryHandler
{
    public function __construct(
        private HistoryEventCategoryRepository $repository
    )
    {
    }

    public function __invoke(GetHistoryEventCategoryQuery $query): HistoryEventCategoryResponse
    {
        $eventCategory = $this->repository->findAll();

        return new HistoryEventCategoryResponse($eventCategory);
    }
}