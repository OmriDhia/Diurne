<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Query\StockExit\GetStockExitList;

use App\Common\Bus\Query\QueryHandler;
use App\MobileAppApi\Repository\StockExitRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class GetStockExitListHandler implements QueryHandler
{
    public function __construct(
        private readonly StockExitRepository $repository
    ) {
    }

    public function __invoke(GetStockExitListQuery $query): array
    {
        return $this->repository->findAll();
    }
}
