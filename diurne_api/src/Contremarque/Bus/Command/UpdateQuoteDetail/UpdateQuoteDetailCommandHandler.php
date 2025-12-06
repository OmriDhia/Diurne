<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\UpdateQuoteDetail;

use InvalidArgumentException;
use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ValidationException;
use App\Contremarque\Entity\CarpetSpecificTreatment;
use App\Contremarque\Repository\CarpetSpecificationRepository;
use App\Contremarque\Repository\CarpetSpecificTreatmentRepository;
use App\Contremarque\Repository\LocationRepository;
use App\Contremarque\Repository\QuoteDetailRepository;
use App\Setting\Entity\SpecialTreatment;
use App\Setting\Repository\CurrencyRepository;
use App\Setting\Repository\SpecialTreatmentRepository;
use App\Setting\Repository\TarifGroupRepository;
use App\Setting\Repository\TarifRepository;
use Doctrine\ORM\EntityManagerInterface;

class UpdateQuoteDetailCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly QuoteDetailRepository $quoteDetailRepository,
        private readonly LocationRepository $locationRepository,
        private readonly CarpetSpecificationRepository $carpetSpecificationRepository,
        private readonly TarifGroupRepository $tarifGroupRepository,
        private readonly CurrencyRepository $currencyRepository,
        private readonly TarifRepository $tarifRepository,
        private readonly SpecialTreatmentRepository $specialTreatmentRepository,
        private readonly CarpetSpecificTreatmentRepository $carpetSpecificTreatmentRepository
    ) {}

    public function __invoke(UpdateQuoteDetailCommand $command): UpdateQuoteDetailResponse
    {
        $quoteDetail = $this->quoteDetailRepository->find($command->quoteDetailId);
        if (!$quoteDetail) {
            throw new InvalidArgumentException('QuoteDetail not found');
        }

        $request = $command->requestDTO;

        if (null !== $request->locationId) {
            $location = $this->locationRepository->find($request->locationId);
            $quoteDetail->setLocation($location);
        }
        if (null !== $request->TarifId) {
            $tarif = $this->tarifRepository->find($request->TarifId);
            $quoteDetail->setTarif($tarif);
        }
        if (null !== $request->currencyId) {
            $currency = $this->currencyRepository->find($request->currencyId);
            $quoteDetail->setCurrency($currency);
        }

        // Check each field for non-empty value before setting
        if (null !== $request->totalPriceRate) {
            $quoteDetail->setTotalPriceRate((string)$request->totalPriceRate ?? (string) 0);
        }
        if (null !== $request->isValidated) {
            $quoteDetail->setValidated($request->isValidated);
        }
        if (null !== $request->wantedQuantity) {
            $quoteDetail->setWantedQuantity($request->wantedQuantity);
        }
        if (null !== $request->estimatedDeliveryTime) {
            $quoteDetail->setEstimatedDeliveryTime($request->estimatedDeliveryTime);
        }
        if (null !== $request->applyLargeProjectRate) {
            $quoteDetail->setApplyLargeProjectRate($request->applyLargeProjectRate);
        }
        if (null !== $request->applyProposedDiscount) {
            $quoteDetail->setApplyProposedDiscount($request->applyProposedDiscount);
        }
        if (null !== $request->proposedDiscountRate) {
            $quoteDetail->setProposedDiscountRate((string)$request->proposedDiscountRate ?? (string)0);
        }
        if (null !== $request->calculateFromTotalExcludingTax) {
            $quoteDetail->setCalculateFromTotalExcludingTax($request->calculateFromTotalExcludingTax ?? (string)0);
        }
        if (null !== $request->inStockCarpet) {
            $quoteDetail->setInStockCarpet($request->inStockCarpet);
        }
        if (!empty($request->comment)) {
            $quoteDetail->setComment($request->comment);
        }

        if (!empty($request->specificTreatmentIds)) {
            foreach ($request->specificTreatmentIds as $treatmentId) {
                $specialTreatment = $this->specialTreatmentRepository->find((int) $treatmentId);
                if ($specialTreatment instanceof SpecialTreatment) {
                    $carpetSpecificTreatment = $this->carpetSpecificTreatmentRepository->findOneBy(['treatment' => $specialTreatment, 'quoteDetail' => $quoteDetail]);
                    if (empty($carpetSpecificTreatment)) {
                        $carpetSpecificTreatment = new CarpetSpecificTreatment();
                    }
                    $carpetSpecificTreatment->setQuoteDetail($quoteDetail);
                    $carpetSpecificTreatment->setUnitPrice($specialTreatment->getPrice());
                    $carpetSpecificTreatment->setTotalPrice((string) ((float) $specialTreatment->getPrice() * $quoteDetail->getSurface()));
                    $carpetSpecificTreatment->setTreatment($specialTreatment);
                    $this->entityManager->persist($carpetSpecificTreatment);
                    $quoteDetail->addCarpetSpecificTreatment($carpetSpecificTreatment);
                }
            }
        }
        if (!empty($request->rn)) {
            $quoteDetail->setRn($request->rn);
        }
        $this->entityManager->persist($quoteDetail);
        $this->entityManager->flush();

        return new UpdateQuoteDetailResponse($quoteDetail);
    }
}
