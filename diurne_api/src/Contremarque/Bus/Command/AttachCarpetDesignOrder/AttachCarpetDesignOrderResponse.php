<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\AttachCarpetDesignOrder;

use App\Common\Bus\Command\CommandResponse;
use App\Contremarque\Entity\QuoteDetail;
use App\Contremarque\Service\ImageProvider; // Add ImageProvider import

class AttachCarpetDesignOrderResponse implements CommandResponse
{
    public function __construct(
        private readonly QuoteDetail $quoteDetail,
        private readonly ImageProvider $imageProvider // Inject ImageProvider as a class dependency
    ) {}

    public function getQuoteDetail(): QuoteDetail
    {
        return $this->quoteDetail;
    }

    public function toArray(): array
    {
        $customerInstruction = $this->quoteDetail->getCarpetDesignOrder()->getCustomerInstruction();

        // Fetch the Vignette path and resized Vignette path
        $vignettePath = $this->imageProvider->getVignettePath($this->quoteDetail->getCarpetDesignOrder());
        $resizedVignettePath = $this->imageProvider->getResizedVignettePath($this->quoteDetail->getCarpetDesignOrder());

        return [
            'quoteDetailId' => $this->quoteDetail->getId(),
            'carpetDesignOrderId' => $this->quoteDetail->getCarpetDesignOrder()?->getId(),
            'customer_validation_date' => !empty($customerInstruction) ? $customerInstruction->getCustomerValidationDate()?->format('d-m-Y') : "",
            'vignettePath' => $vignettePath, // Add Vignette path
            'resizedVignettePath' => $resizedVignettePath, // Add Resized Vignette path
        ];
    }
}
