<?php

declare(strict_types=1);

namespace App\Contremarque\Service\QuoteCloner;

use App\Contremarque\Entity\Quote;
use App\Contremarque\Entity\QuoteDetail;
use App\Contremarque\Repository\CarpetOrderRepository;
use App\Contremarque\Repository\CarpetSpecificationRepository;
use App\Contremarque\Service\CarpetReferenceGenerator;
use App\Contremarque\Repository\QuoteDetailRepository;
use Doctrine\ORM\EntityManagerInterface;

class QuoteCloner
{
    private const BATCH_SIZE = 50;
    private const SIMULATOR_BATCH_SIZE = 200;

    public function __construct(
        private readonly EntityManagerInterface        $entityManager,
        private readonly CarpetOrderRepository         $carpetOrderRepository,
        private readonly QuoteDetailRepository         $quoteDetailRepository,
        private readonly CarpetSpecificationRepository $carpetSpecificationRepository,
        private readonly CarpetReferenceGenerator $referenceGenerator,
    )
    {
    }

    public function cloneQuoteForOrder(Quote $original): Quote
    {
        $this->entityManager->getConnection()->getConfiguration()->setSQLLogger(null);
        $this->entityManager->beginTransaction();

        try {
            $clonedQuote = clone $original;
            $reference = $this->carpetOrderRepository->getNextCarpetOrderNumber();

            $clonedQuote->setIsCloneOf($original->getId());
            $clonedQuote->setUsedInOrder(1);
            $clonedQuote->setReference($reference);
            $clonedQuote->setTransformedIntoAnOrder(false);
            $clonedQuote->setCreatedAt(new \DateTimeImmutable());
            $clonedQuote->setArchived(false);

            $this->entityManager->persist($clonedQuote);
            $this->entityManager->flush();

            // Clone quote details
            $detailCounter = 1;
            foreach ($original->getQuoteDetails() as $originalDetail) {
                $detailReference = $this->quoteDetailRepository->getNextCarpetNumberInQuote($reference) . '-' . $detailCounter;
                $clonedDetail = $this->cloneQuoteDetail($originalDetail, $detailReference, $detailCounter);
                $clonedQuote->addQuoteDetail($clonedDetail);
                if ($detailCounter % self::BATCH_SIZE === 0) {
                    $this->entityManager->flush();
                    $this->entityManager->clear();
                    // Re-fetch the cloned quote to maintain the reference
                }

                $detailCounter++;
            }

            $this->entityManager->flush();
            $this->entityManager->commit();

            return $clonedQuote;
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            throw $e;
        }
    }

    private function cloneQuoteDetail(QuoteDetail $original, string $reference, int $detailCounter): QuoteDetail
    {
        $cloned = clone $original;
        $cloned->setReference($reference);

        // Clone CarpetSpecification if it exists
        if ($original->getCarpetSpecification() !== null) {
            $clonedSpec = clone $original->getCarpetSpecification();
            $newCarpetRef = $this->referenceGenerator->getNextReference() . '-' . $detailCounter;
            $clonedSpec->setCarpetReference($newCarpetRef);

            $this->entityManager->persist($clonedSpec);
            $cloned->setCarpetSpecification($clonedSpec);

            // Clone dimensions
            foreach ($original->getCarpetSpecification()->getCarpetDimensions() as $dimension) {
                $clonedDim = clone $dimension;
                $this->entityManager->persist($clonedDim);
                $clonedSpec->addCarpetDimension($clonedDim);
            }
        }

        $this->entityManager->persist($cloned);

        // Process price bases and simulators in optimized batches
        $priceBases = $original->getCarpetPriceBases();
        $priceBaseCount = 0;

        foreach ($priceBases as $priceBase) {

            $clonedPrice = clone $priceBase;
            $this->entityManager->persist($clonedPrice);
            $cloned->addCarpetPriceBase($clonedPrice);

            // Clone simulators
            /* $simulatorCount = 0;
                   foreach ($priceBase->getPriceSimulators() as $simulator) {

                       $clonedSim = clone $simulator;
                       $clonedSim->setBasePrice($clonedPrice);
                       $this->entityManager->persist($clonedSim);

                       if (++$simulatorCount % self::SIMULATOR_BATCH_SIZE === 0) {
                           $this->entityManager->flush();
                           $this->entityManager->clear();
                           // Re-fetch the cloned entities to maintain references

                       }
                   }*/

            if (++$priceBaseCount % self::BATCH_SIZE === 0) {
                $this->entityManager->flush();
                $this->entityManager->clear();
            }
        }

        $this->entityManager->flush();
        return $cloned;
    }
}
