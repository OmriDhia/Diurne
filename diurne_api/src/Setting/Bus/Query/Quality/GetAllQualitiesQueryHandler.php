<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\Quality;

use App\Common\Bus\Query\QueryHandler;
use App\Setting\Repository\QualityRepository;

class GetAllQualitiesQueryHandler implements QueryHandler
{
    public function __construct(private readonly QualityRepository $qualityRepository)
    {
    }

    public function __invoke(GetAllQualitiesQuery $query): QualityQueryResponse
    {
        $qualityRepository = $this->qualityRepository;
        $qualities = $qualityRepository->findAll();

        return new QualityQueryResponse($qualities);
    }
}
