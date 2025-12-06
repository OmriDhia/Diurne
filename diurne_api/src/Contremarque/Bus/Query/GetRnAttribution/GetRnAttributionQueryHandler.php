<?php

namespace App\Contremarque\Bus\Query\GetRnAttribution;

use App\Common\Bus\Query\QueryHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Contremarque\Repository\RnAttributionRepository;

class GetRnAttributionQueryHandler implements QueryHandler
{
    /**
     * @param RnAttributionRepository $rnAttributionRepository
     */
    public function __construct(
        private readonly RnAttributionRepository $rnAttributionRepository
    )
    {
    }

    /**
     * @param GetRnAttributionQuery $query
     * @return GetRnAttributionResponse
     * @throws ResourceNotFoundException
     */
    public function __invoke(GetRnAttributionQuery $query): GetRnAttributionResponse
    {
        $rnAttributions = $this->rnAttributionRepository->findAll();

        if (empty($rnAttributions)) {
            throw new ResourceNotFoundException('No RnAttributions found');
        }

        return new GetRnAttributionResponse($rnAttributions);
    }
}