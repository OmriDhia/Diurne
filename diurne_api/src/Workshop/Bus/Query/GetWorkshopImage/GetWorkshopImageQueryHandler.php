<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Query\GetWorkshopImage;


use App\Common\Bus\Query\QueryHandler;
use App\Workshop\Repository\WorkshopImageRepository;

class GetWorkshopImageQueryHandler implements QueryHandler
{
    /**
     * @param WorkshopImageRepository $workshopImageRepository
     */
    public function __construct(private readonly WorkshopImageRepository $workshopImageRepository)
    {
    }

    /**
     * Handle the query to fetch all workshop images.
     *
     * @param GetWorkshopImageQuery $query
     * @return GetWorkshopImageResponse
     */
    public function __invoke(GetWorkshopImageQuery $query): GetWorkshopImageResponse
    {
        $workshopImg = $this->workshopImageRepository->findAll();

        return new GetWorkshopImageResponse($workshopImg);
    }

}