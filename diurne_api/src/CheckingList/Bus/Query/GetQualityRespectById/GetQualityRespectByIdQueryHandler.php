<?php

namespace App\CheckingList\Bus\Query\GetQualityRespectById;

use App\CheckingList\Repository\QualityRespectRepository;
use App\Common\Bus\Query\QueryHandler;
use App\Common\Exception\ResourceNotFoundException;

class GetQualityRespectByIdQueryHandler implements QueryHandler
{
    public function __construct(private readonly QualityRespectRepository $repository)
    {
    }

    public function __invoke(GetQualityRespectByIdQuery $query): GetQualityRespectByIdResponse
    {
        $respect = $this->repository->find($query->id);
        if (!$respect) {
            throw new ResourceNotFoundException();
        }

        return new GetQualityRespectByIdResponse($respect);
    }
}
