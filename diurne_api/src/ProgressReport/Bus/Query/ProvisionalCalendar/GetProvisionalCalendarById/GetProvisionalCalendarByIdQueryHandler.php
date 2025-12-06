<?php

namespace App\ProgressReport\Bus\Query\ProvisionalCalendar\GetProvisionalCalendarById;

use App\Common\Bus\Query\QueryHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\ProgressReport\Repository\ProvisionalCalendarRepository;

class GetProvisionalCalendarByIdQueryHandler implements QueryHandler
{
    public function __construct(private ProvisionalCalendarRepository $repository)
    {
    }

    public function __invoke(GetProvisionalCalendarByIdQuery $query): GetProvisionalCalendarByIdResponse
    {
        $calendar = $this->repository->find($query->id);
        if (!$calendar) {
            throw new ResourceNotFoundException();
        }

        return new GetProvisionalCalendarByIdResponse($calendar);
    }
}
