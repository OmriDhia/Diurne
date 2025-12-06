<?php

namespace App\CheckingList\Bus\Query\GetLayersValidation;

use App\CheckingList\Repository\LayersValidationRepository;
use App\Common\Bus\Query\QueryHandler;

class GetLayersValidationQueryHandler implements QueryHandler
{
    public function __construct(private readonly LayersValidationRepository $repository)
    {
    }

    public function __invoke(GetLayersValidationQuery $query): GetLayersValidationResponse
    {
        $list = $this->repository->findAll();
        return new GetLayersValidationResponse($list);
    }
}
