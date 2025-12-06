<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\TriggerStatus;

use RuntimeException;
use App\Common\Bus\Command\CommandHandler;
use App\Contremarque\Repository\QuoteDetailRepository;
use Doctrine\ORM\EntityManagerInterface;

class TriggerStatusHandler implements CommandHandler
{
    public function __construct(
        private readonly QuoteDetailRepository $quoteDetailRepository,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function __invoke(TriggerStatusCommand $command): TriggerStatusResponse
    {
        // Fetch the QuoteDetail entity by ID
        $quoteDetail = $this->quoteDetailRepository->find($command->quoteDetailId);

        if (!$quoteDetail) {
            throw new RuntimeException("QuoteDetail with ID {$command->quoteDetailId} not found.");
        }

        // Update the status
        $quoteDetail->setActive($command->newStatus);

        // Persist changes
        $this->entityManager->persist($quoteDetail);
        $this->entityManager->flush();

        // Return the TriggerStatusResponse
        return new TriggerStatusResponse(
            $quoteDetail->getId(),
            $quoteDetail->isActive()
        );
    }
}
