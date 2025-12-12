<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Command\StockEntry\UpdateStockEntry;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Bus\Command\CommandResponse;
use App\Common\Exception\ResourceNotFoundException;
use App\MobileAppApi\Repository\StockEntryRepository;
use App\MobileAppApi\Repository\RNRepository;
use App\MobileAppApi\Repository\UserMobileAppRepository;
use Doctrine\ORM\EntityManagerInterface;

final class UpdateStockEntryHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly StockEntryRepository $stockEntryRepository,
        private readonly RNRepository $rnRepository,
        private readonly UserMobileAppRepository $userRepository
    ) {
    }

    public function __invoke(UpdateStockEntryCommand $command): CommandResponse
    {
        $stockEntry = $this->stockEntryRepository->find($command->id);

        if (!$stockEntry) {
            throw new ResourceNotFoundException('Stock Entry not found');
        }

        if ($command->rnId !== null) {
            $rn = $this->rnRepository->find($command->rnId);
            if ($rn) {
                $stockEntry->setRn($rn);
            }
        }

        if ($command->userId !== null) {
            $user = $this->userRepository->find($command->userId);
            if ($user) {
                $stockEntry->setUser($user);
            }
        }

        if ($command->width !== null) {
            $stockEntry->setWidth($command->width);
        }

        if ($command->height !== null) {
            $stockEntry->setHeight($command->height);
        }

        if ($command->quality !== null) {
            $stockEntry->setQuality($command->quality);
        }

        if ($command->color !== null) {
            $stockEntry->setColor($command->color);
        }

        if ($command->location !== null) {
            $stockEntry->setLocation($command->location);
        }
        
        if ($command->photoId !== null) {
            // Assuming Photo Handling if needed, logic here
        }

        $stockEntry->setUpdatedAt(new \DateTimeImmutable());

        $this->entityManager->flush();

        return new UpdateStockEntryResponse($stockEntry);
    }
}
