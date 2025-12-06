<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\DeleteQuoteDetail;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Contremarque\Repository\QuoteDetailRepository;
use App\Contremarque\Repository\QuoteRepository;
use Doctrine\ORM\EntityManagerInterface;

final class DeleteQuoteDetailCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly QuoteRepository $quoteRepository,
        private readonly QuoteDetailRepository $quoteDetailRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(DeleteQuoteDetailCommand $command): void
    {
        $quote = $this->quoteRepository->find($command->getQuoteId());
        if (null === $quote) {
            throw new ResourceNotFoundException('Quote not found');
        }

        $quoteDetail = $this->quoteDetailRepository->find($command->getQuoteDetailId());
        if (null === $quoteDetail || $quoteDetail->getQuote()?->getId() !== $quote->getId()) {
            throw new ResourceNotFoundException('QuoteDetail not found for the provided quote');
        }

        $quote->removeQuoteDetail($quoteDetail);
        $this->entityManager->remove($quoteDetail);
        $this->entityManager->flush();
    }
}
