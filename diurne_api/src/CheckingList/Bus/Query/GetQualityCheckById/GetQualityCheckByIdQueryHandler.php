<?php

namespace App\CheckingList\Bus\Query\GetQualityCheckById;

use App\CheckingList\Repository\QualityCheckRepository;
use App\Common\Bus\Query\QueryHandler;
use App\Common\Exception\ResourceNotFoundException;

class GetQualityCheckByIdQueryHandler implements QueryHandler
{
    public function __construct(private readonly QualityCheckRepository $repository)
    {
    }

    public function __invoke(GetQualityCheckByIdQuery $query): GetQualityCheckByIdResponse
    {
        $qualityCheck = $this->repository->find($query->id);
        if (!$qualityCheck) {
            throw new ResourceNotFoundException();
        }

        return new GetQualityCheckByIdResponse($qualityCheck);
    }
}
