<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\TransportType;

use App\Common\Bus\Query\QueryHandler;
use App\Setting\Repository\TransportTypeRepository;

class GetAllTransportTypeQueryHandler implements QueryHandler
{
    public function __construct(private readonly TransportTypeRepository $transportTypeRepository)
    {
    }

    public function __invoke(GetAllTransportTypeQuery $query): TransportTypeQueryResponse
    {
        $all_transportType = $this->transportTypeRepository->findAll();

        return new TransportTypeQueryResponse($all_transportType);
    }
}
