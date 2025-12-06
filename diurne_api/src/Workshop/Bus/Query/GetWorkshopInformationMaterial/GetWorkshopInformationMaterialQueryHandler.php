<?php

declare(strict_types=1);

namespace App\Workshop\Bus\Query\GetWorkshopInformationMaterial;

use App\Common\Bus\Query\QueryHandler;
use App\Workshop\Repository\WorkshopInformationMaterialRepository;

class GetWorkshopInformationMaterialQueryHandler implements QueryHandler
{
    public function __construct(private readonly WorkshopInformationMaterialRepository $repository)
    {
    }

    public function __invoke(GetWorkshopInformationMaterialQuery $query): WorkshopInformationMaterialResponse
    {
        $workshopInformationMaterials = $this->repository->findAll();

        return new WorkshopInformationMaterialResponse($workshopInformationMaterials);
    }
}
