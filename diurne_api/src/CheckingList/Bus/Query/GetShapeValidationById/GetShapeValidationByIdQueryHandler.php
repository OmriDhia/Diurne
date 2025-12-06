<?php

namespace App\CheckingList\Bus\Query\GetShapeValidationById;

use App\CheckingList\Repository\ShapeValidationRepository;
use App\Common\Bus\Query\QueryHandler;
use App\Common\Exception\ResourceNotFoundException;

class GetShapeValidationByIdQueryHandler implements QueryHandler
{
    public function __construct(private readonly ShapeValidationRepository $repository)
    {
    }

    public function __invoke(GetShapeValidationByIdQuery $query): GetShapeValidationByIdResponse
    {
        $entity = $this->repository->find($query->id);
        if (!$entity) {
            throw new ResourceNotFoundException();
        }

        return new GetShapeValidationByIdResponse($entity);
    }
}
