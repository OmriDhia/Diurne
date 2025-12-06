<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Query\GetWorkshopOrderById;


use App\Common\Bus\Query\QueryHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Workshop\Entity\WorkshopOrder;
use App\Workshop\Repository\WorkshopOrderRepository;


class GetWorkshopOrderByIdQueryHandler implements QueryHandler
{
    /**
     * @param WorkshopOrderRepository $workshopOrderRepository
     */
    public function __construct(
        private readonly WorkshopOrderRepository $workshopOrderRepository
    )
    {
    }

    /**
     * @param GetWorkshopOrderByIdQuery $query
     * @return GetWorkshopOrderByIdResponse
     * @throws ResourceNotFoundException
     */
    public function __invoke(GetWorkshopOrderByIdQuery $query): GetWorkshopOrderByIdResponse
    {
        $workshopOrder = $this->workshopOrderRepository->find($query->WorkshopOrderId);
        if ($workshopOrder === null) {
            throw new ResourceNotFoundException();
        }
        return new GetWorkshopOrderByIdResponse($workshopOrder);
    }

}