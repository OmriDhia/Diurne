<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Command\StockEntry\CreateStockEntry;

use App\Common\Bus\Command\CommandHandler;
use App\MobileAppApi\Entity\StockEntry;
use App\MobileAppApi\Entity\RN;
use App\MobileAppApi\Entity\UserMobileApp;
use App\MobileAppApi\Repository\StockEntryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use App\Common\Bus\Command\CommandResponse;

#[AsMessageHandler]
final class CreateStockEntryHandler implements CommandHandler
{
    public function __construct(
        private readonly StockEntryRepository $repository,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function __invoke(CreateStockEntryCommand $command): CommandResponse
    {
        $rn = $this->entityManager->getRepository(RN::class)->find($command->rnId);
        if (!$rn) {
            throw new NotFoundHttpException('RN not found');
        }

        $stock_entry = new StockEntry();
        $stock_entry->setRn($rn);
        $stock_entry->setLocation($command->location);
        
        if ($command->userId) {
            $user = $this->entityManager->getReference(UserMobileApp::class, $command->userId);
            $stock_entry->setUser($user);
        }

        $this->repository->save($stock_entry, true);

        return new CreateStockEntryResponse($stock_entry);
    }
}
