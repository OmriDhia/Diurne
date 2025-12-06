<?php

namespace App\CheckingList\Bus\Query\GetQualityRespect;

use App\CheckingList\Repository\QualityRespectRepository;
use App\Common\Bus\Query\QueryHandler;

class GetQualityRespectQueryHandler implements QueryHandler
{
    public function __construct(private readonly QualityRespectRepository $repository)
    {
    }

    public function __invoke(GetQualityRespectQuery $query): GetQualityRespectResponse
    {
        $list = $this->repository->findAll();
        return new GetQualityRespectResponse($list);
    }
}
