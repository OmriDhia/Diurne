<?php

declare(strict_types=1);

namespace App\Workshop\Bus\Query\GetCarpets;

use App\Common\Bus\Query\QueryHandler;
use App\Workshop\Repository\CarpetRepository;

class GetCarpetsQueryHandler implements QueryHandler
{
    public function __construct(
        private CarpetRepository $repository
    )
    {
    }

    public function __invoke(GetCarpetsQuery $query): CarpetsResponse
    {
        $carpets = $this->repository->findAll();
        // You could add filtering based on query parameters here

        return new CarpetsResponse($carpets);
    }
}