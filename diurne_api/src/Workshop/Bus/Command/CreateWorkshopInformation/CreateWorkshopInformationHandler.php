<?php

namespace App\Workshop\Bus\Command\CreateWorkshopInformation;


use App\Common\Bus\Command\CommandHandler;
use App\Workshop\Entity\WorkshopInformation;
use App\Workshop\Repository\WorkshopInformationRepository;
use App\Setting\Repository\QualityRepository;
use App\Setting\Repository\TarifGroupRepository;
use App\Setting\Repository\CurrencyRepository;
use Doctrine\ORM\EntityManagerInterface;


class CreateWorkshopInformationHandler implements CommandHandler
{
    /**
     * @param EntityManagerInterface $entityManager
     * @param WorkshopInformationRepository $repository
     */
    public function __construct(
        private readonly EntityManagerInterface        $entityManager,
        private readonly WorkshopInformationRepository $repository,
        private readonly QualityRepository             $qualityRepository,
        private readonly TarifGroupRepository          $tarifGroupRepository,
        private readonly CurrencyRepository            $currencyRepository,
    )
    {
    }

    /**
     * @param CreateWorkshopInformationCommand $command
     * @return WorkshopInformationResponse
     */
    public function __invoke(CreateWorkshopInformationCommand $command): WorkshopInformationResponse
    {
        $workshopInfo = new WorkshopInformation();
        $quality = $this->qualityRepository->find($command->idQuality);
        $tarifGroup = $this->tarifGroupRepository->find($command->idTarifGroup);
        $currency = $command->currencyId ? $this->currencyRepository->find($command->currencyId) : null;
        if ($quality) {
            $workshopInfo->setQuality($quality);
        }
        if ($tarifGroup) {
            $workshopInfo->setTarifGroup($tarifGroup);
        }
        if ($currency) {
            $workshopInfo->setCurrency($currency);
        }
        $workshopInfo->setLaunchDate($command->launchDate);
        $workshopInfo->setExpectedEndDate($command->expectedEndDate);
        $workshopInfo->setDateEndAtelierPrev($command->dateEndAtelierPrev);
        //$workshopInfo->setProductionTime($command->productionTime);
        $workshopInfo->setOrderSilkPercentage($command->orderSilkPercentage);
        $workshopInfo->setOrderedWidth($command->orderedWidth);
        $workshopInfo->setOrderedHeigh($command->orderedHeigh);
        $workshopInfo->setOrderedSurface($command->orderedSurface);
        $workshopInfo->setRealWidth($command->realWidth);
        $workshopInfo->setRealHeight($command->realHeight);
        $workshopInfo->setRealSurface($command->realSurface);
        //$workshopInfo->setIdTarifGroup($command->idTarifGroup);
        $workshopInfo->setReductionRate($command->reductionRate);
        $workshopInfo->setUpcharge($command->upcharge);
        $workshopInfo->setCommentUpcharge($command->commentUpcharge);
        $workshopInfo->setCarpetPurchasePricePerM2($command->carpetPurchasePricePerM2);
        $workshopInfo->setCarpetPurchasePriceCmd($command->carpetPurchasePriceCmd);
        $workshopInfo->setCarpetPurchasePriceTheoretical($command->carpetPurchasePriceTheoretical);
        $workshopInfo->setCarpetPurchasePriceInvoice($command->carpetPurchasePriceInvoice);
        $workshopInfo->setPenalty($command->penalty);
        $workshopInfo->setShipping($command->shipping);
        $workshopInfo->setTva($command->tva);
        $workshopInfo->setAvailableForSale($command->availableForSale);
        $workshopInfo->setSent($command->sent);
        $workshopInfo->setReceivedInParis($command->receivedInParis);
        if ($command->specialRate !== null) {
            $workshopInfo->setSpecialRate($command->specialRate);
        }
        $workshopInfo->setGrossMargin($command->grossMargin);
        $workshopInfo->setReferenceOnInvoice($command->referenceOnInvoice);
        $workshopInfo->setInvoiceNumber($command->invoiceNumber);
        $workshopInfo->setManufacturerId($command->manufacturerId);
        $workshopInfo->setCopy($command->copy);
        $workshopInfo->setRn($command->rn);
        $this->entityManager->persist($workshopInfo);
        $this->entityManager->flush();
        return new WorkshopInformationResponse ($workshopInfo);
    }
}