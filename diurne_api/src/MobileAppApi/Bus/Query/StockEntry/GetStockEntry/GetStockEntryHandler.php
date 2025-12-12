<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Query\StockEntry\GetStockEntry;

use App\Common\Bus\Query\QueryHandler;
use App\MobileAppApi\Entity\StockEntry;
use App\MobileAppApi\Repository\StockEntryRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[AsMessageHandler]
final class GetStockEntryHandler implements QueryHandler
{
    public function __construct(
        private readonly StockEntryRepository $repository
    ) {
    }

    public function __invoke(GetStockEntryQuery $query): StockEntry
    {
        $stock_entry = $this->repository->find($query->id);

        if (!$stock_entry) {
            throw new NotFoundHttpException('StockEntry not found');
        }

        return $stock_entry;
    }
}
