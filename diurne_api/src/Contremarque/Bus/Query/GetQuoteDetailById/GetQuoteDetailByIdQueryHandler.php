<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetQuoteDetailById;

use App\Common\Bus\Query\QueryHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Contremarque\Repository\QuoteDetailRepository;
use App\Contremarque\Service\ImageProvider;
use App\Setting\Entity\DiscountRule;
use App\Setting\Repository\TarifRepository;

/**
 * This class is responsible for handling the 'get quoteDetail by ID' query.
 */
final readonly class GetQuoteDetailByIdQueryHandler implements QueryHandler
{
    /**
     * Constructor with QuoteDetailRepository injection.
     *
     * @param QuoteDetailRepository $quoteDetailRepository quoteDetail repository interface
     */
    public function __construct(
        private QuoteDetailRepository $quoteDetailRepository,
        private TarifRepository $tarifRepository,
        private ImageProvider $imageProvider
    ) {}

    /**
     * Handles the 'get quoteDetail by ID' query.
     *
     * @param GetQuoteDetailByIdQuery $query the query object containing the quoteDetail ID
     *
     * @return GetQuoteDetailByIdResponse the response object with quoteDetail details
     *
     * @throws ResourceNotFoundException thrown when the quoteDetail is not found
     */
    public function __invoke(GetQuoteDetailByIdQuery $query): GetQuoteDetailByIdResponse
    {

        $quoteDetail = $this->quoteDetailRepository->findOneById((int) $query->quoteDetailId());
        if (null === $quoteDetail) {
            throw new ResourceNotFoundException();
        }

        $quoteDetailData = $quoteDetail->toArray();
        $customer = $quoteDetail->getQuote()->getContremarque()->getCustomer();
        if (!$customer) {
            throw new ResourceNotFoundException(sprintf('Customer not found for this quote %s', $quoteDetail->getQuote()->getId()));
        }
        $discountRule = $customer->getDiscountRule();
        if ($discountRule instanceof DiscountRule) {
            $tarif = $this->tarifRepository->getLastTarifByDiscountRule($discountRule);
            if ($tarif) {
                $quoteDetailData['lastTarif'] = $tarif->toArray();
                $quoteDetailData['defaultCustomTarifId'] = $tarif->getId();
            }
        }
        if (!empty($quoteDetail->getCarpetDesignOrder())) {
            $customerInstruction = $quoteDetail->getCarpetDesignOrder()->getCustomerInstruction();

            // Fetch the Vignette path and resized Vignette path
            $vignettePath = $this->imageProvider->getVignettePath($quoteDetail->getCarpetDesignOrder());
            $resizedVignettePath = $this->imageProvider->getResizedVignettePath($quoteDetail->getCarpetDesignOrder());

            $quoteDetailData = array_merge($quoteDetailData,  [
                'quoteDetailId' => $quoteDetail->getId(),
                'carpetDesignOrderId' => $quoteDetail->getCarpetDesignOrder()?->getId(),
                'customer_validation_date' => $customerInstruction ? $customerInstruction->getCustomerValidationDate()?->format('d-m-Y') : '',
                'vignettePath' => $vignettePath, // Add Vignette path
                'resizedVignettePath' => $resizedVignettePath, // Add Resized Vignette path
            ]);
        }



        return new GetQuoteDetailByIdResponse($quoteDetailData);
    }
}
