<?php

namespace App\CheckingList\Bus\Query\GetShapeValidation;

use App\CheckingList\Repository\ShapeValidationRepository;
use App\Common\Bus\Query\QueryHandler;

class GetShapeValidationQueryHandler implements QueryHandler
{
    public function __construct(private readonly ShapeValidationRepository $repository)
    {
    }

    public function __invoke(GetShapeValidationQuery $query): GetShapeValidationResponse
    {
        $list = $this->repository->findAll();
        return new GetShapeValidationResponse($list);
    }
}
