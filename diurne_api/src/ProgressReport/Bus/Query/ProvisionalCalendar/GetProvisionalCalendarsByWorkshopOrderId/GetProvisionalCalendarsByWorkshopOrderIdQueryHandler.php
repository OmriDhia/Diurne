<?php

declare(strict_types=1);

namespace App\ProgressReport\Bus\Query\ProvisionalCalendar\GetProvisionalCalendarsByWorkshopOrderId;

use App\Common\Bus\Query\QueryHandler;
use App\ProgressReport\Bus\Query\ProvisionalCalendar\ProvisionalCalendarQueryResponse;
use App\ProgressReport\Repository\ProvisionalCalendarRepository;
use App\Workshop\Repository\WorkshopOrderRepository;

final class GetProvisionalCalendarsByWorkshopOrderIdQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly ProvisionalCalendarRepository $calendarRepository,
        private readonly WorkshopOrderRepository $workshopOrderRepository,
    ) {
    }

    public function __invoke(GetProvisionalCalendarsByWorkshopOrderIdQuery $query): ProvisionalCalendarQueryResponse
    {
        $workshopOrder = $this->workshopOrderRepository->find($query->workshopOrderId);
        if (!$workshopOrder) {
            return new ProvisionalCalendarQueryResponse([]);
        }

        $calendars = $this->calendarRepository->findBy(['workshopOrder' => $workshopOrder]);

        return new ProvisionalCalendarQueryResponse($calendars);
    }
}
