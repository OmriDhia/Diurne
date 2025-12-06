<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetDiStatus;

use App\Common\Bus\Query\QueryHandler;
use App\Contremarque\Repository\DiStatusRepository;

final readonly class GetDiStatusQueryHandler implements QueryHandler
{
    public function __construct(private DiStatusRepository $diStatusRepository)
    {
    }

    public function __invoke(GetDiStatusQuery $query)
    {
        $diStatuses = $this->diStatusRepository->findAll();

        $formattedDiStatuses = array_map(fn($diStatus) => $diStatus->toArray(), $diStatuses);

        return new GetDiStatusResponse($formattedDiStatuses);
    }
}
