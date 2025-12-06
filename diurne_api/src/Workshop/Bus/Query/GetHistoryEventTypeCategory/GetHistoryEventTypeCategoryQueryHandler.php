<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Query\GetHistoryEventTypeCategory;


use App\Common\Bus\Query\QueryHandler;
use App\Workshop\Entity\HistoryEventTypeCategory;
use App\Workshop\Repository\HistoryEventTypeCategoryRepository;

class GetHistoryEventTypeCategoryQueryHandler implements QueryHandler
{
    /**
     * @param HistoryEventTypeCategoryRepository $repository
     */
    public function __construct(
        private HistoryEventTypeCategoryRepository $repository
    )
    {
    }

    /**
     * @param GetHistoryEventTypeCategoryQuery $query
     * @return HistoryEventTypeCategoryResponse
     */
    public function __invoke(GetHistoryEventTypeCategoryQuery $query): HistoryEventTypeCategoryResponse
    {
        $allCategories = $this->repository->findAll();
        return new HistoryEventTypeCategoryResponse($allCategories);
    }

}