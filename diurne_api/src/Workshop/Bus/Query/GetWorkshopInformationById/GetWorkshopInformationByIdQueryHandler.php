<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Query\GetWorkshopInformationById;


use App\Common\Bus\Query\QueryHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Workshop\Repository\WorkshopInformationRepository;


class GetWorkshopInformationByIdQueryHandler implements QueryHandler
{
    /**
     * @param WorkshopInformationRepository $workshopInformationRepository
     */
    public function __construct(
        private WorkshopInformationRepository $workshopInformationRepository
    )
    {
    }

    /**
     * @param GetWorkshopInformationByIdQuery $query
     * @return GetWorkshopInformationByIdResponse
     * @throws ResourceNotFoundException
     */
    public function __invoke(GetWorkshopInformationByIdQuery $query): GetWorkshopInformationByIdResponse
    {
        $workshopInfo = $this->workshopInformationRepository->find($query->WorkshopInformationId);
        if ($workshopInfo === null) {
            throw new ResourceNotFoundException();
        }
        return new GetWorkshopInformationByIdResponse($workshopInfo);
    }

}