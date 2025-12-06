<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Query\GetHistoryEventTypeCategoryByid;

use App\Common\Bus\Query\QueryHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Workshop\Entity\HistoryEventTypeCategory;
use App\Workshop\Repository\HistoryEventTypeCategoryRepository;

class GetHistoryEventTypeCategoryByIdQueryHandler implements QueryHandler
{
    /**
     * @param HistoryEventTypeCategoryRepository $repository
     */
    public function __construct(
        private readonly HistoryEventTypeCategoryRepository $repository
    )
    {
    }

    /**
     * @param GetHistoryEventTypeCategoryByIdQuery $query
     * @return HistoryEventTypeCategoryResponse
     * @throws ResourceNotFoundException
     */
    public function __invoke(GetHistoryEventTypeCategoryByIdQuery $query): HistoryEventTypeCategoryResponse
    {
        $category = $this->repository->find($query->id);
        if ($category === null) {
            throw new ResourceNotFoundException();
        }
        return new HistoryEventTypeCategoryResponse($category);
    }
}