<?php

namespace App\CheckingList\Bus\Query\GetQualityCheck;

use App\CheckingList\Repository\QualityCheckRepository;
use App\Common\Bus\Query\QueryHandler;

class GetQualityCheckQueryHandler implements QueryHandler
{
    public function __construct(private readonly QualityCheckRepository $repository)
    {
    }

    public function __invoke(GetQualityCheckQuery $query): GetQualityCheckResponse
    {
        $list = $this->repository->findAll();
        return new GetQualityCheckResponse($list);
    }
}
