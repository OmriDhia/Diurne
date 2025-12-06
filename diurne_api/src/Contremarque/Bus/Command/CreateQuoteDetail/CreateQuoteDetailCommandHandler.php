<?php

namespace App\Contremarque\Bus\Command\CreateQuoteDetail;

use App\Common\Bus\Command\CommandHandler;
use App\Contremarque\Entity\CarpetSpecificTreatment;
use App\Contremarque\Entity\QuoteDetail;
use App\Contremarque\Repository\CarpetSpecificationRepository;
use App\Contremarque\Repository\LocationRepository;
use App\Contremarque\Repository\QuoteDetailRepository;
use App\Contremarque\Repository\QuoteRepository;
use App\Setting\Entity\SpecialTreatment;
use App\Setting\Repository\CurrencyRepository;
use App\Setting\Repository\SpecialTreatmentRepository;
use App\Setting\Repository\TarifRepository;
use Doctrine\ORM\EntityManagerInterface;

class CreateQuoteDetailCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly QuoteDetailRepository $quoteDetailRepository,
        private readonly QuoteRepository $quoteRepository,
        private readonly LocationRepository $locationRepository,
        private readonly CarpetSpecificationRepository $carpetSpecificationRepository,
        private readonly TarifRepository $tarifRepository,
        private readonly CurrencyRepository $currencyRepository,
        private readonly SpecialTreatmentRepository $specialTreatmentRepository
    ) {}

    public function __invoke(CreateQuoteDetailCommand $command): CreateQuoteDetailResponse
    {
        // Fetch related entities (Quote, Location, etc.)
        $quote = $this->quoteRepository->find((int) $command->quoteId);
        $location = $this->locationRepository->find((int) $command->locationId);
        $tarif = $this->tarifRepository->find((int) $command->tarifId);
        $currency = $this->currencyRepository->find((int) $command->currencyId);
        $quoteDetailReference = $command->reference;
        if (empty($quoteDetailReference)) {
            $quoteDetailReference = $this->quoteDetailRepository->getNextCarpetNumberInQuote($quote->getReference());
        }

        // Create a new QuoteDetail entity
        $quoteDetail = new QuoteDetail();
        $quoteDetail->setQuote($quote)
            ->setReference($quoteDetailReference)
            ->setLocation($location)
            ->setTarif($tarif)
            ->setValidated($command->isValidated)
            ->setValidatedAt($command->validatedAt)
            ->setWantedQuantity($command->wantedQuantity)
            ->setEstimatedDeliveryTime($command->estimatedDeliveryTime)
            ->setApplyLargeProjectRate($command->applyLargeProjectRate)
            ->setApplyProposedDiscount($command->applyProposedDiscount)
            ->setCurrency($currency)
            ->setCalculateFromTotalExcludingTax($command->calculateFromTotalExcludingTax)
            ->setInStockCarpet($command->inStockCarpet)
            ->setComment($command->comment);
        if (!empty($command->proposedDiscountRate)) {
            $quoteDetail->setProposedDiscountRate((string) $command->proposedDiscountRate);
        }
        if (!empty($command->totalPriceRate)) {
            $quoteDetail->setTotalPriceRate((string) $command->totalPriceRate);
        }
        if (!empty($command->specificTreatmentIds)) {
            foreach ($command->specificTreatmentIds as $treatmentId) {
                $specialTreatment = $this->specialTreatmentRepository->find((int) $treatmentId);
                if ($specialTreatment instanceof SpecialTreatment) {
                    $carpetSpecificTreatment = new CarpetSpecificTreatment();
                    $carpetSpecificTreatment->setQuoteDetail($quoteDetail);
                    $carpetSpecificTreatment->setUnitPrice($specialTreatment->getPrice());
                    $carpetSpecificTreatment->setTreatment($specialTreatment);
                    $this->entityManager->persist($carpetSpecificTreatment);
                    $quoteDetail->addCarpetSpecificTreatment($carpetSpecificTreatment);
                }
            }
        }
        if (!empty($command->rn)) {
            $quoteDetail->setRn($command->rn);
        }

        // Persist the entity
        $this->entityManager->persist($quoteDetail);
        $this->entityManager->flush();

        return new CreateQuoteDetailResponse($quoteDetail);
    }
}
