<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Command\StockEntry\DeleteStockEntry;

use App\Common\Bus\Command\CommandHandler;
use App\MobileAppApi\Repository\StockEntryRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[AsMessageHandler]
final class DeleteStockEntryHandler implements CommandHandler
{
    public function __construct(
        private readonly StockEntryRepository $repository
    ) {
    }

    public function __invoke(DeleteStockEntryCommand $command): void
    {
        $stock_entry = $this->repository->find($command->id);

        if (!$stock_entry) {
            throw new NotFoundHttpException('StockEntry not found');
        }

        $this->repository->remove($stock_entry, true);
    }
}
