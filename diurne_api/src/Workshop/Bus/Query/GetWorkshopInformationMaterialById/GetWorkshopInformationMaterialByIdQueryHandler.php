<?php

declare(strict_types=1);

namespace App\Workshop\Bus\Query\GetWorkshopInformationMaterialById;

use App\Common\Bus\Query\QueryHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Workshop\Repository\WorkshopInformationMaterialRepository;

class GetWorkshopInformationMaterialByIdQueryHandler implements QueryHandler
{
    public function __construct(private readonly WorkshopInformationMaterialRepository $repository)
    {
    }

    /**
     * @throws ResourceNotFoundException
     */
    public function __invoke(GetWorkshopInformationMaterialByIdQuery $query): WorkshopInformationMaterialByIdResponse
    {
        $material = $this->repository->find($query->id);

        if ($material === null) {
            throw new ResourceNotFoundException();
        }

        return new WorkshopInformationMaterialByIdResponse($material);
    }
}
