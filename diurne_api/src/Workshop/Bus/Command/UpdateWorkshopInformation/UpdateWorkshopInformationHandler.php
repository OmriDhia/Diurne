<?php

namespace App\Workshop\Bus\Command\UpdateWorkshopInformation;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Workshop\Repository\WorkshopInformationRepository;
use App\Workshop\Repository\CarpetRepository;
use App\Workshop\Entity\WorkshopOrder;
use Doctrine\ORM\EntityManagerInterface;
use App\Setting\Repository\QualityRepository;
use App\Setting\Repository\TarifGroupRepository;
use App\Setting\Repository\CurrencyRepository;
use RuntimeException;


class UpdateWorkshopInformationHandler implements CommandHandler
{
    /**
     * @param WorkshopInformationRepository $repository
     */
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private WorkshopInformationRepository   $repository,
        private readonly QualityRepository      $qualityRepository,
        private readonly TarifGroupRepository   $tarifGroupRepository,
        private readonly CurrencyRepository     $currencyRepository,
        private readonly CarpetRepository       $carpetRepository,
    )
    {
    }


    /**
     * @param UpdateWorkshopInformationCommand $command
     * @return UpdateWorkshopInformationResponse
     * @throws ResourceNotFoundException
     */
    public function __invoke(UpdateWorkshopInformationCommand $command): UpdateWorkshopInformationResponse
    {

        $workshopInfo = $this->repository->find($command->id);

        if (!$workshopInfo) {
            throw new ResourceNotFoundException();
        }

        // Update only the fields that were provided in the command
        if ($command->launchDate !== null) {
            $workshopInfo->setLaunchDate($command->launchDate);
        }
        if ($command->expectedEndDate !== null) {
            $workshopInfo->setExpectedEndDate($command->expectedEndDate);
        }
        if ($command->dateEndAtelierPrev !== null) {
            $workshopInfo->setDateEndAtelierPrev($command->dateEndAtelierPrev);
        }
        /*if ($command->productionTime !== null) {
            $workshopInfo->setProductionTime($command->productionTime);
        }*/
        if ($command->orderSilkPercentage !== null) {
            $workshopInfo->setOrderSilkPercentage($command->orderSilkPercentage);
        }
        if ($command->orderedWidth !== null) {
            $workshopInfo->setOrderedWidth($command->orderedWidth);
        }
        if ($command->orderedHeigh !== null) {
            $workshopInfo->setOrderedHeigh($command->orderedHeigh);
        }
        if ($command->orderedSurface !== null) {
            $workshopInfo->setOrderedSurface($command->orderedSurface);
        }
        if ($command->realWidth !== null) {
            $workshopInfo->setRealWidth($command->realWidth);
        }
        if ($command->realHeight !== null) {
            $workshopInfo->setRealHeight($command->realHeight);
        }
        if ($command->realSurface !== null) {
            $workshopInfo->setRealSurface($command->realSurface);
        }
        /*if ($command->idTarifGroup !== null) {
            $workshopInfo->setIdTarifGroup($command->idTarifGroup);
        }*/
        if ($command->reductionRate !== null) {
            $workshopInfo->setReductionRate($command->reductionRate);
        }
        if ($command->upcharge !== null) {
            $workshopInfo->setUpcharge($command->upcharge);
        }
        if ($command->commentUpcharge !== null) {
            $workshopInfo->setCommentUpcharge($command->commentUpcharge);
        }
        if ($command->carpetPurchasePricePerM2 !== null) {
            $workshopInfo->setCarpetPurchasePricePerM2($command->carpetPurchasePricePerM2);
        }
        if ($command->carpetPurchasePriceCmd !== null) {
            $workshopInfo->setCarpetPurchasePriceCmd($command->carpetPurchasePriceCmd);
        }
        if ($command->carpetPurchasePriceTheoretical !== null) {
            $workshopInfo->setCarpetPurchasePriceTheoretical($command->carpetPurchasePriceTheoretical);
        }
        if ($command->carpetPurchasePriceInvoice !== null) {
            $workshopInfo->setCarpetPurchasePriceInvoice($command->carpetPurchasePriceInvoice);
        }
        if ($command->penalty !== null) {
            $workshopInfo->setPenalty($command->penalty);
        }
        if ($command->shipping !== null) {
            $workshopInfo->setShipping($command->shipping);
        }
        if ($command->tva !== null) {
            $workshopInfo->setTva($command->tva);
        }
        if ($command->availableForSale !== null) {
            $workshopInfo->setAvailableForSale($command->availableForSale);
        }
        if ($command->sent !== null) {
            $workshopInfo->setSent($command->sent);
        }
        if ($command->receivedInParis !== null) {
            $workshopInfo->setReceivedInParis($command->receivedInParis);
        }
        if ($command->specialRate !== null) {
            $workshopInfo->setSpecialRate($command->specialRate);
        }
        if ($command->grossMargin !== null) {
            $workshopInfo->setGrossMargin($command->grossMargin);
        }
        if ($command->referenceOnInvoice !== null) {
            $workshopInfo->setReferenceOnInvoice($command->referenceOnInvoice);
        }
        if ($command->invoiceNumber !== null) {
            $workshopInfo->setInvoiceNumber($command->invoiceNumber);
        }
        if ($command->currencyId !== null) {
            $currency = $this->currencyRepository->find($command->currencyId);
            if ($currency) {
                $workshopInfo->setCurrency($currency);
            }
        }
        if ($command->idQuality !== null) {
            $quality = $this->qualityRepository->find($command->idQuality);
            if ($quality) {
                $workshopInfo->setQuality($quality);
            }
        }
        if ($command->idTarifGroup !== null) {
            $tarifGroup = $this->tarifGroupRepository->find($command->idTarifGroup);
            if ($tarifGroup) {
                $workshopInfo->setTarifGroup($tarifGroup);
            }
        }
        if ($command->manufacturerId !== null) {
            $workshopInfo->setManufacturerId($command->manufacturerId);
        }
        if ($command->copy !== null) {
            $workshopInfo->setCopy($command->copy);
        }
        if ($command->rn !== null) {
            $workshopOrder = $workshopInfo->getWorkshopOrder();

            $this->ensureRnIsUnique($command->rn, $workshopOrder);
            $this->updateCarpetsRn($command->rn, $workshopOrder);
            $workshopInfo->setRn($command->rn);
        }

        $this->entityManager->flush();
        return new UpdateWorkshopInformationResponse($workshopInfo);
    }

    private function ensureRnIsUnique(string $rn, ?WorkshopOrder $workshopOrder): void
    {
        $existingCarpet = $this->carpetRepository->findOneBy(['rnNumber' => $rn]);

        if (
            $existingCarpet !== null
            && $existingCarpet->getWorkshopOrder()?->getId() !== $workshopOrder?->getId()
        ) {
            throw new RuntimeException('RN existe déjà.');
        }
    }

    private function updateCarpetsRn(string $rn, ?WorkshopOrder $workshopOrder): void
    {
        if ($workshopOrder === null) {
            return;
        }

        $carpets = $this->carpetRepository->findBy(['workshopOrder' => $workshopOrder]);

        foreach ($carpets as $carpet) {
            $carpet->setRnNumber($rn);
        }
    }
}