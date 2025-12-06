<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\CloneQuoteDetail;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Contremarque\Entity\QuoteDetail;
use App\Contremarque\Repository\QuoteDetailRepository;
use App\Contremarque\Service\QuoteDetail\QuoteDetailCloner;

class CloneQuoteDetailCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly QuoteDetailRepository $quoteDetailRepository,
        private readonly QuoteDetailCloner $cloner,
    ) {
    }

    public function __invoke(CloneQuoteDetailCommand $command): CloneQuoteDetailResponse
    {
        $original = $this->quoteDetailRepository->findOneById($command->getQuoteDetailId());
        if (!$original instanceof QuoteDetail) {
            throw new ResourceNotFoundException('QuoteDetail not found.');
        }

        $quote = $original->getQuote();
        $reference = $this->quoteDetailRepository->getNextCarpetNumberInQuote($quote->getReference());

        $cloned = $this->cloner->clone($original, $reference);
        $cloned->setQuote($quote);

        $this->quoteDetailRepository->save($cloned);

        return new CloneQuoteDetailResponse($cloned);
    }
}
