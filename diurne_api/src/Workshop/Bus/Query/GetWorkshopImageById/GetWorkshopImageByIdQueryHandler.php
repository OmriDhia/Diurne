<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Query\GetWorkshopImageById;


use App\Common\Bus\Query\QueryHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Workshop\Entity\WorkshopImage;
use App\Workshop\Repository\WorkshopImageRepository;

class GetWorkshopImageByIdQueryHandler implements QueryHandler
{
    /**
     * @param WorkshopImageRepository $workshopImageRepository
     */
    public function __construct(private readonly WorkshopImageRepository $workshopImageRepository)
    {
    }

    /**
     * @param GetWorkshopImageByIdQuery $query
     * @return GetWorkshopImageByIdResponse
     * @throws ResourceNotFoundException
     */
    public function __invoke(GetWorkshopImageByIdQuery $query): GetWorkshopImageByIdResponse
    {
        $workshopImg = $this->workshopImageRepository->find($query->WorkshopImageId);

        if ($workshopImg === null) {
            throw new ResourceNotFoundException();
        }
        return new GetWorkshopImageByIdResponse($workshopImg);
    }

}