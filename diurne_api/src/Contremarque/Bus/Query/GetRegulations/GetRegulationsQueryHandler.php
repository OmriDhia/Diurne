<?php
declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetRegulations;

use App\Common\Bus\Query\QueryHandler;
use App\Contremarque\Repository\RegulationRepository;

class GetRegulationsQueryHandler implements QueryHandler
{
    /**
     * @param RegulationRepository $repository
     */
    public function __construct(private readonly RegulationRepository $repository)
    {
    }

    /**
     * @param GetRegulationsQuery $query
     * @return GetRegulationsResponse
     */
    public function __invoke(GetRegulationsQuery $query): GetRegulationsResponse
    {
        $list = $this->repository->findAll();

        return new GetRegulationsResponse($list);
    }
}
