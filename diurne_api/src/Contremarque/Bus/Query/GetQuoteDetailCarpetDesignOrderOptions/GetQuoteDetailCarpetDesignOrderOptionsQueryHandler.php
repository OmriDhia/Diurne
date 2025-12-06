<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetQuoteDetailCarpetDesignOrderOptions;

use App\Common\Bus\Query\QueryHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Contremarque\Repository\CarpetDesignOrderRepository;
use App\Contremarque\Repository\QuoteDetailRepository;

/**
 * This class is responsible for handling the 'get quote by ID' query.
 */
final readonly class GetQuoteDetailCarpetDesignOrderOptionsQueryHandler implements QueryHandler
{
    /**
     * Constructor with QuoteRepository injection.
     *
     * @param QuoteRepository $quoteRepository quote repository interface
     */
    public function __construct(
        private CarpetDesignOrderRepository $carpetDesignOrderRepository,
        private QuoteDetailRepository $quoteDetailRepository
    ) {}


    /**
     *
     * Handles the 'get quote carpet design order options' query.
     *
     * @param GetQuoteDetailCarpetDesignOrderOptionsQuery $query the query object containing the quote ID
     *
     * @return GetQuoteDetailCarpetDesignOrderOptionsResponse the response object with quote data
     *
     * @throws ResourceNotFoundException thrown when the quote is not found
     */
    public function __invoke(GetQuoteDetailCarpetDesignOrderOptionsQuery $query): GetQuoteDetailCarpetDesignOrderOptionsResponse
    {
        $quoteDetail = $this->quoteDetailRepository->find((int)$query->quoteDetailId());
        if ($quoteDetail === null) {
            throw new ResourceNotFoundException();
        }


        $designOrderCollection = $this->carpetDesignOrderRepository->getCarpetDesignOrderOptions($quoteDetail);

        if (${$designOrderCollection} === null) {
            throw new ResourceNotFoundException();
        }

        return new GetQuoteDetailCarpetDesignOrderOptionsResponse($designOrderCollection);
    }
}
