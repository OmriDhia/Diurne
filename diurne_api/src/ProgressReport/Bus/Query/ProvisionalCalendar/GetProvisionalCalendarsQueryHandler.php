<?php

namespace App\ProgressReport\Bus\Query\ProvisionalCalendar;

use App\Common\Bus\Query\QueryHandler;
use App\ProgressReport\Repository\ProvisionalCalendarRepository;

class GetProvisionalCalendarsQueryHandler implements QueryHandler
{
    public function __construct(private ProvisionalCalendarRepository $repository)
    {
    }

    public function __invoke(GetProvisionalCalendarsQuery $query): ProvisionalCalendarQueryResponse
    {
        $calendars = $this->repository->findAll();
        return new ProvisionalCalendarQueryResponse($calendars);
    }
}

