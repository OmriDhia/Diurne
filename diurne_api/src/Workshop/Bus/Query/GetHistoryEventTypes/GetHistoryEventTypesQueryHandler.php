<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Query\GetHistoryEventTypes;

use App\Common\Bus\Query\QueryHandler;
use App\Workshop\Entity\HistoryEventType;
use App\Workshop\Repository\HistoryEventTypeRepository;

class GetHistoryEventTypesQueryHandler implements QueryHandler
{
    public function __construct(
        private HistoryEventTypeRepository $repository
    )
    {
    }

    public function __invoke(GetHistoryEventTypesQuery $query): HistoryEventTypesResponse
    {

        $eventTypes = $this->repository->findAll();


        return new HistoryEventTypesResponse($eventTypes);
    }
}