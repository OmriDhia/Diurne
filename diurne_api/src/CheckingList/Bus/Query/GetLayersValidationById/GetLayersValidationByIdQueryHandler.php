<?php

namespace App\CheckingList\Bus\Query\GetLayersValidationById;

use App\CheckingList\Repository\LayersValidationRepository;
use App\Common\Bus\Query\QueryHandler;
use App\Common\Exception\ResourceNotFoundException;

class GetLayersValidationByIdQueryHandler implements QueryHandler
{
    public function __construct(private readonly LayersValidationRepository $repository)
    {
    }

    public function __invoke(GetLayersValidationByIdQuery $query): GetLayersValidationByIdResponse
    {
        $entity = $this->repository->find($query->id);
        if (!$entity) {
            throw new ResourceNotFoundException();
        }

        return new GetLayersValidationByIdResponse($entity);
    }
}
