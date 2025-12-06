<?php

namespace App\Contremarque\Bus\Query\GetCarpetOrderDetailById;

use App\Common\Bus\Query\QueryHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Contremarque\Repository\CarpetOrderDetailRepository;
use App\Contremarque\Repository\QuoteDetailRepository;

use RuntimeException;

class GetCarpetOrderDetailByIdQueryHandler implements QueryHandler
{
    /**
     * @param CarpetOrderDetailRepository $carpetOrderDetailRepository
     * @param QuoteDetailRepository $quoteDetailRepository
     */
    public function __construct(
        private readonly CarpetOrderDetailRepository $carpetOrderDetailRepository,
        private readonly QuoteDetailRepository       $quoteDetailRepository
    )
    {
    }

    /**
     * @param GetCarpetOrderDetailByIdQuery $query
     * @return GetCarpetOrderDetailByIdResponse
     */
    public function __invoke(GetCarpetOrderDetailByIdQuery $query): GetCarpetOrderDetailByIdResponse
    {
        try {
            // First get the Quote entity
            $quote = $this->quoteDetailRepository->find($query->getClonedQuoteDetailId());
            if ($quote === null) {
                throw new ResourceNotFoundException();
            }
            // Then find the CarpetOrder by the Quote entity
            $carpetOrderDetail = $this->carpetOrderDetailRepository->findOneBy(['quote_detail' => $quote]);
            if ($carpetOrderDetail === null) {
                throw new ResourceNotFoundException('Carpet order not found for this quote');
            }
            return new GetCarpetOrderDetailByIdResponse($carpetOrderDetail);
        } catch (\Exception $e) {
            throw new RuntimeException('Failed to fetch carpet order: ' . $e->getMessage());
        }
    }
}