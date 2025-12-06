<?php

namespace App\Setting\Bus\Query\GetAllSpecialShape;

use App\Common\Bus\Query\QueryHandler;
use App\Setting\Repository\SpecialShapeRepository;

class GetAllSpecialShapeQueryHandler implements QueryHandler
{
    public function __construct(private readonly SpecialShapeRepository $specialShapeRepository)
    {
    }

    public function __invoke(GetAllSpecialShapeQuery $query): GetAllSpecialShapeResponse
    {
        // Fetch all price types from the repository
        $specialShapes = $this->specialShapeRepository->findAll();

        return new GetAllSpecialShapeResponse($specialShapes);
    }
}
