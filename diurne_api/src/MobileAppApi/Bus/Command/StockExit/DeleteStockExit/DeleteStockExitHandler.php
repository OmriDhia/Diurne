<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Command\StockExit\DeleteStockExit;

use App\Common\Bus\Command\CommandHandler;
use App\MobileAppApi\Repository\StockExitRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[AsMessageHandler]
final class DeleteStockExitHandler implements CommandHandler
{
    public function __construct(
        private readonly StockExitRepository $repository
    ) {
    }

    public function __invoke(DeleteStockExitCommand $command): void
    {
        $stockExit = $this->repository->find($command->id);

        if (!$stockExit) {
            throw new NotFoundHttpException('StockExit not found');
        }

        $this->repository->remove($stockExit, true);
    }
}
