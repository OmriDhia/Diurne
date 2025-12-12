<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Query\StockEntry\GetStockEntryList;

use App\Common\Bus\Query\QueryHandler;
use App\MobileAppApi\Repository\StockEntryRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class GetStockEntryListHandler implements QueryHandler
{
    public function __construct(
        private readonly StockEntryRepository $repository
    ) {
    }

    public function __invoke(GetStockEntryListQuery $query): array
    {
        return $this->repository->findAll();
    }
}
