<?php

declare(strict_types=1);

namespace App\Contremarque\Service\QuoteDetail;

use App\Contremarque\Entity\QuoteDetail;
use App\Contremarque\Service\CarpetReferenceGenerator;

class QuoteDetailCloner
{
    public function __construct(
        private readonly CarpetReferenceGenerator $referenceGenerator,
    ) {
    }

    public function clone(QuoteDetail $original, string $reference): QuoteDetail
    {
        $cloned = clone $original;
        $cloned->setReference($reference);

        if ($original->getCarpetSpecification() !== null) {
            $clonedSpec = clone $original->getCarpetSpecification();
            $clonedSpec->setCarpetReference($this->referenceGenerator->getNextReference());

            foreach ($original->getCarpetSpecification()->getCarpetDimensions() as $dimension) {
                $clonedSpec->addCarpetDimension(clone $dimension);
            }

            $cloned->setCarpetSpecification($clonedSpec);
        }

        foreach ($original->getCarpetPriceBases() as $priceBase) {
            $cloned->addCarpetPriceBase(clone $priceBase);
        }

        return $cloned;
    }
}
