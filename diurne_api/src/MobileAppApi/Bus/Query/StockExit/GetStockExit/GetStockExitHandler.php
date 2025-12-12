<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Query\StockExit\GetStockExit;

use App\Common\Bus\Query\QueryHandler;
use App\MobileAppApi\Entity\StockExit;
use App\MobileAppApi\Repository\StockExitRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[AsMessageHandler]
final class GetStockExitHandler implements QueryHandler
{
    public function __construct(
        private readonly StockExitRepository $repository
    ) {
    }

    public function __invoke(GetStockExitQuery $query): StockExit
    {
        $stockExit = $this->repository->find($query->id);

        if (!$stockExit) {
            throw new NotFoundHttpException('StockExit not found');
        }

        return $stockExit;
    }
}
