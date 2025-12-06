<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Query\GetHistoryEventTypeById;

use App\Common\Bus\Query\QueryHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Workshop\Repository\HistoryEventTypeRepository;

class GetHistoryEventTypeByIdQueryHandler implements QueryHandler
{
    /**
     * @param HistoryEventTypeRepository $repository
     */
    public function __construct(
        private readonly HistoryEventTypeRepository $repository
    )
    {
    }

    /**
     * @throws ResourceNotFoundException
     */
    public function __invoke(GetHistoryEventTypeByIdQuery $query): HistoryEventTypeResponse
    {
        $eventType = $this->repository->find($query->id);
        if ($eventType === null) {
            throw new ResourceNotFoundException();
        }
        return new HistoryEventTypeResponse($eventType);
    }

}