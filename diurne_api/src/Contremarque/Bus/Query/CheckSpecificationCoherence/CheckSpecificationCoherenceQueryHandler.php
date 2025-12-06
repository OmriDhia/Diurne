<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\CheckSpecificationCoherence;

use App\Common\Bus\Query\QueryHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Contremarque\Repository\CarpetDesignOrderRepository;
use App\Contremarque\Repository\QuoteDetailRepository;
use App\Contremarque\Service\CheckSpecificationCoherence\CheckSpecificationCoherence;

final class CheckSpecificationCoherenceQueryHandler implements QueryHandler
{
    public function __construct(
        private CarpetDesignOrderRepository $carpetDesignOrderRepository,
        private QuoteDetailRepository       $quoteDetailRepository,
        private CheckSpecificationCoherence $checkSpecificationCoherence

    )
    {
    }

    public function __invoke(CheckSpecificationCoherenceQuery $query): CheckSpecificationCoherenceResponse
    {
        $carpetDesignOrder = $this->carpetDesignOrderRepository->find($query->getCarpetDesignOrderId());
        if (!$carpetDesignOrder) {
            throw new ResourceNotFoundException('CarpetDesignOrder not found');
        }

        $quoteDetail = $this->quoteDetailRepository->find($query->getQuoteDetailId());
        if (!$quoteDetail) {
            throw new ResourceNotFoundException('QuoteDetail not found');
        }
        return $this->checkSpecificationCoherence->check($carpetDesignOrder, $quoteDetail);

    }

}