<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Query\GetHistoryEventCategoryById;

use App\Common\Bus\Query\QueryHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Workshop\Repository\HistoryEventCategoryRepository;


class GetHistoryEventCategoryByIdQueryHandler implements QueryHandler
{
    /**
     * @param HistoryEventCategoryRepository $repository
     */
    public function __construct(
        private readonly HistoryEventCategoryRepository $repository
    )
    {
    }

    /**
     * @param GetHistoryEventCategoryByIdQuery $query
     * @return HistoryEventCategoryResponse
     * @throws ResourceNotFoundException
     */
    public function __invoke(GetHistoryEventCategoryByIdQuery $query): HistoryEventCategoryResponse
    {
        $eventCategory = $this->repository->find($query->id);
        if ($eventCategory === null) {
            throw new ResourceNotFoundException();
        }
        return new HistoryEventCategoryResponse($eventCategory);
    }

}