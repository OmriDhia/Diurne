<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetCarpetStatuses;

use App\Common\Bus\Query\QueryHandler;
use App\Contremarque\Repository\CarpetStatusRepository;

final readonly class GetCarpetStatusesQueryHandler implements QueryHandler
{
    public function __construct(private CarpetStatusRepository $carpetStatusRepository)
    {
    }

    public function __invoke(GetCarpetStatusesQuery $query)
    {
        $statuses = $this->carpetStatusRepository->findAll();

        $formattedStatuses = array_map(fn($status) => $status->toArray(), $statuses);

        return new GetCarpetStatusesResponse($formattedStatuses);
    }
}
