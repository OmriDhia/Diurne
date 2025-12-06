<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Query\GetWorkshopRnHistoryById;

use App\Common\Bus\Query\QueryHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Workshop\Entity\WorkshopRnHistory;
use App\Workshop\Repository\WorkshopRnHistoryRepository;

class GetWorkshopRnHistoryByIdQueryHandler implements QueryHandler
{
    /**
     * @param WorkshopRnHistoryRepository $repository
     */
    public function __construct(
        private WorkshopRnHistoryRepository $repository
    )
    {
    }

    /**
     * @param GetWorkshopRnHistoryByIdQuery $query
     * @return GetWorkshopRnHistoryByIdResponse
     * @throws ResourceNotFoundException
     */
    public function __invoke(GetWorkshopRnHistoryByIdQuery $query): GetWorkshopRnHistoryByIdResponse
    {
        $workshopRnHistory = $this->repository->find($query->workshopRnHistoryId);
        if ($workshopRnHistory === null) {
            throw new ResourceNotFoundException();
        }
        return new GetWorkshopRnHistoryByIdResponse($workshopRnHistory);
    }

}