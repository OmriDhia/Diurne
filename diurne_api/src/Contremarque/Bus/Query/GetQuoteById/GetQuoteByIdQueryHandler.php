<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetQuoteById;

use App\Common\Bus\Query\QueryHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Contremarque\Repository\QuoteRepository;
use App\Contremarque\Repository\CarpetOrderRepository;
use App\Setting\Entity\DiscountRule;
use App\Setting\Repository\TarifRepository;

/**
 * This class is responsible for handling the 'get quote by ID' query.
 */
final readonly class GetQuoteByIdQueryHandler implements QueryHandler
{
    /**
     * Constructor with QuoteRepository injection.
     *
     * @param QuoteRepository $quoteRepository quote repository interface
     */
    public function __construct(
        private QuoteRepository $quoteRepository,
        private TarifRepository $tarifRepository,
        private CarpetOrderRepository $carpetOrderRepository
    ) {}

    /**
     * Handles the 'get quote by ID' query.
     *
     * @param GetQuoteByIdQuery $query the query object containing the quote ID
     *
     * @return GetQuoteByIdResponse the response object with quote details
     *
     * @throws ResourceNotFoundException thrown when the quote is not found
     */
    public function __invoke(GetQuoteByIdQuery $query): GetQuoteByIdResponse
    {

        $quoteObject = $this->quoteRepository->find((int) $query->quoteId());

        if (null === $quoteObject) {
            throw new ResourceNotFoundException();
        }

        $quoteData = $quoteObject->toArray();
        $customer = $quoteObject->getContremarque()->getCustomer();
        if (!$customer) {
            throw new ResourceNotFoundException(sprintf('Customer not found for this quote %s', $quoteObject->getId()));
        }
        $discountRule = $customer->getDiscountRule();
        if ($discountRule instanceof DiscountRule) {
            $tarif = $this->tarifRepository->getLastTarifByDiscountRule($discountRule);
            if ($tarif) {
                $quoteData['lastTarif'] = $tarif->toArray();
                $quoteData['defaultCustomTarifId'] = $tarif->getId();
            }
        }

        $originalQuoteData = null;

        $carpetOrder = $this->carpetOrderRepository->findOneBy(['clonedQuote' => $quoteObject]);
        if (null !== $carpetOrder) {
            $originalQuote = $carpetOrder->getOriginalQuote();
            if (null !== $originalQuote) {
                $originalQuoteData = [
                    'id' => $originalQuote->getId(),
                    'reference' => $originalQuote->getReference(),
                    'quoteDetailReferences' => $originalQuote->getQuoteDetails()
                        ->map(static fn($detail) => $detail->getReference())
                        ->toArray(),
                ];
            }
        }

        return new GetQuoteByIdResponse($quoteData, $originalQuoteData);
    }
}
