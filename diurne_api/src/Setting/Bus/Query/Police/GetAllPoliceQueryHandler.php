<?php

namespace App\Setting\Bus\Query\Police;

use App\Common\Bus\Query\QueryHandler;
use App\Setting\Repository\PoliceRepository;

class GetAllPoliceQueryHandler implements QueryHandler
{
    public function __construct(private readonly PoliceRepository $policeRepository)
    {
    }

    public function __invoke(GetAllPoliceQuery $query): PoliceQueryResponse
    {
        $policeEntities = $this->policeRepository->findAll();

        return new PoliceQueryResponse($policeEntities);
    }
}
