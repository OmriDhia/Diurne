<?php

namespace App\Contremarque\Bus\Query\GetCarpetOrderById;

use App\Common\Bus\Query\QueryHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Contremarque\Repository\CarpetOrderRepository;
use App\Contremarque\Repository\QuoteRepository;
use RuntimeException;

class GetCarpetOrderByIdQueryHandler implements QueryHandler
{
    /**
     * @param CarpetOrderRepository $carpetOrderRepository
     * @param QuoteRepository $quoteRepository
     */
    public function __construct(
        private readonly CarpetOrderRepository $carpetOrderRepository,
        private readonly QuoteRepository       $quoteRepository
    )
    {
    }

    /**
     * @param GetCarpetOrderByIdQuery $query
     * @return GetCarpetOrderByIdResponse
     */
    public function __invoke(GetCarpetOrderByIdQuery $query): GetCarpetOrderByIdResponse
    {
        try {
            // First get the Quote entity
            $quote = $this->quoteRepository->find($query->getClonedQuoteId());
            if ($quote === null) {
                throw new ResourceNotFoundException();
            }
            // Then find the CarpetOrder by the Quote entity
            $carpetOrder = $this->carpetOrderRepository->findOneBy(['clonedQuote' => $quote]);
            if ($carpetOrder === null) {
                throw new ResourceNotFoundException('Carpet order not found for this quote');
            }
            return new GetCarpetOrderByIdResponse($carpetOrder);
        } catch (\Exception $e) {
            throw new RuntimeException('Failed to fetch carpet order: ' . $e->getMessage());
        }
    }
}