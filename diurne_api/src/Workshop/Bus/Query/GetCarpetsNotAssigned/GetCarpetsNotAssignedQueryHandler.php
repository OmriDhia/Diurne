<?php

declare(strict_types=1);

namespace App\Workshop\Bus\Query\GetCarpetsNotAssigned;

use App\Common\Bus\Query\QueryHandler;
use App\Workshop\Repository\CarpetRepository;

final class GetCarpetsNotAssignedQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly CarpetRepository $repository,
    ) {
    }

    public function __invoke(GetCarpetsNotAssignedQuery $query): CarpetsNotAssignedResponse
    {
        $carpets = $this->repository->findBy(['carpetOrderDetail' => null]);

        return new CarpetsNotAssignedResponse($carpets);
    }
}
