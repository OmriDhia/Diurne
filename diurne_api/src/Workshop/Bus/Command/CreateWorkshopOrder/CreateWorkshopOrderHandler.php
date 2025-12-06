<?php

namespace App\Workshop\Bus\Command\CreateWorkshopOrder;

use App\Contremarque\Entity\ImageCommand\ImageCommand;
use App\Contremarque\Repository\ImageCommandRepository;
use App\Workshop\Entity\Carpet;
use DateTime;
use App\Common\Bus\Command\CommandHandler;
use App\Workshop\Entity\WorkshopInformation;
use App\Workshop\Entity\WorkshopOrder;
use App\Workshop\Repository\WorkshopInformationRepository;
use App\Workshop\Repository\WorkshopOrderRepository;
use App\Workshop\Repository\CarpetRepository;
use App\Setting\Entity\ManufacturerPriceGrid;
use App\Setting\Repository\ManufacturerPriceGridRepository;
use App\Setting\Repository\ManufacturerPriceRepository;
use App\Workshop\Entity\MaterialPurchasePrice;
use App\Workshop\Entity\WorkshopInformationMaterial;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use RuntimeException;

class CreateWorkshopOrderHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface          $entityManager,
        private readonly WorkshopOrderRepository         $orderRepository,
        private readonly WorkshopInformationRepository   $informationRepository,
        private readonly ImageCommandRepository          $imageCommandRepository,
        private readonly ManufacturerPriceGridRepository $manufacturerPriceGridRepository,
        private readonly ManufacturerPriceRepository     $manufacturerPriceRepository,
        private readonly CarpetRepository                $carpetRepository,
    )
    {
    }

    public function __invoke(CreateWorkshopOrderCommand $command): CreateWorkshopOrderResponse
    {
        $workshopInformation = $this->informationRepository->find($command->workshopInformationId);
        $imageCommand = $this->imageCommandRepository->find($command->imageCommandId);
        if (!$workshopInformation) {
            throw new EntityNotFoundException(
                sprintf('WorkshopInformation with id %d not found', $command->workshopInformationId)
            );
        }
        if (!$imageCommand) {
            throw new EntityNotFoundException(
                sprintf('$imageCommand  with id %d not found', $command->imageCommandId)
            );
        }

        $manufacturerId = $workshopInformation->getManufacturerId();
        if ($manufacturerId === null) {
            throw new RuntimeException('Manufacturer is required to generate RN numbers for workshop orders.');
        }

        $copyCount = max(1, $workshopInformation->getCopy());
        $originalRn = $workshopInformation->getRn();
        $rnData = $this->generateRnNumbers($workshopInformation, $manufacturerId, $copyCount);
        $rnNumbers = $rnData['numbers'];
        $baseRn = $rnData['base'];
        $prototypeCarpet = $this->findPrototypeCarpet($originalRn, $baseRn, $rnNumbers);

        $quality = $workshopInformation->getQuality();
        $priceGrid = $this->getManufacturerPriceGrid($workshopInformation);
        $carpetSpec = $imageCommand->getCarpetDesignOrder()?->getCarpetSpecification();

        $orders = [];
        for ($index = 0; $index < $copyCount; $index++) {
            $currentInformation = $index === 0
                ? $workshopInformation
                : $this->duplicateWorkshopInformation($workshopInformation, $manufacturerId);

            if ($index > 0) {
                $this->entityManager->persist($currentInformation);
            }

            $currentInformation->setCopy(1);
            $currentInformation->setRn($rnNumbers[$index]);

            $workshopOrder = new WorkshopOrder();
            $workshopOrder->setReference($this->formatReference($command->reference, $index + 1, $copyCount));
            $workshopOrder->setImageCommand($imageCommand);
            $workshopOrder->setCreatedAt($command->createdAt ? clone $command->createdAt : new DateTime());
            $workshopOrder->setUpdatedAt($command->updatedAt ? clone $command->updatedAt : new DateTime());

            $currentInformation->setWorkshopOrder($workshopOrder);

            $this->entityManager->persist($workshopOrder);

            if ($carpetSpec && $priceGrid) {
                $this->createMaterialPurchasePrices($carpetSpec, $priceGrid, $currentInformation, $workshopOrder);
            }

            if ($carpetSpec) {
                $this->createWorkshopInformationMaterials($carpetSpec, $currentInformation, $priceGrid ?? null);
            }

            $carpet = $this->resolveOrCreateCarpet(
                $rnNumbers[$index],
                $imageCommand,
                $index,
                $prototypeCarpet
            );
            $carpet->setWorkshopOrder($workshopOrder);

            $orders[] = $workshopOrder;
        }

        $this->entityManager->flush();

        return new CreateWorkshopOrderResponse($orders);
    }

    /**
     * @return array{numbers: string[], base: string|null}
     */
    private function generateRnNumbers(WorkshopInformation $workshopInformation, int $manufacturerId, int $copyCount): array
    {
        if ($copyCount <= 1) {
            $existing = $workshopInformation->getRn();
            if ($existing) {
                return ['numbers' => [$existing], 'base' => $existing];
            }

            $next = $this->carpetRepository->getNextRnNumber($manufacturerId);

            return ['numbers' => [$next], 'base' => $next];
        }

        $existing = $workshopInformation->getRn();
        if ($existing !== null && $existing !== '') {
            $dashPosition = strpos($existing, '-');
            $baseRn = $dashPosition === false ? $existing : substr($existing, 0, $dashPosition);
        } else {
            $baseRn = $this->carpetRepository->getNextRnNumber($manufacturerId);
        }
        $rnNumbers = [];
        for ($position = 1; $position <= $copyCount; $position++) {
            $rnNumbers[] = sprintf('%s-%d', $baseRn, $position);
        }

        return ['numbers' => $rnNumbers, 'base' => $baseRn];
    }

    private function findPrototypeCarpet(?string $originalRn, ?string $baseRn, array $rnNumbers): ?Carpet
    {
        $candidates = [];

        if ($originalRn !== null && $originalRn !== '') {
            $candidates[] = $originalRn;
            $dashPosition = strpos($originalRn, '-');
            if ($dashPosition !== false) {
                $candidates[] = substr($originalRn, 0, $dashPosition);
            }
        }

        if ($baseRn !== null && $baseRn !== '') {
            $candidates[] = $baseRn;
        }

        foreach ($rnNumbers as $rnNumber) {
            $candidates[] = $rnNumber;
            $dashPosition = strpos($rnNumber, '-');
            if ($dashPosition !== false) {
                $candidates[] = substr($rnNumber, 0, $dashPosition);
            }
        }

        $candidates = array_unique(array_filter($candidates));

        foreach ($candidates as $candidate) {
            $carpet = $this->carpetRepository->findOneBy(['rnNumber' => $candidate]);
            if ($carpet) {
                return $carpet;
            }
        }

        return null;
    }

    private function resolveOrCreateCarpet(
        string       $rnNumber,
        ImageCommand $imageCommand,
        int          $position,
        ?Carpet      &$prototypeCarpet
    ): Carpet
    {
        $carpet = $this->carpetRepository->findOneBy(['rnNumber' => $rnNumber]);
        if ($carpet) {
            if ($position === 0 && $prototypeCarpet === null) {
                $prototypeCarpet = $carpet;
            }

            if ($carpet->getImageCommand() === null) {
                $carpet->setImageCommand($imageCommand);
            }

            return $carpet;
        }

        if ($position === 0 && $prototypeCarpet !== null) {
            if ($prototypeCarpet->getRnNumber() !== $rnNumber) {
                if ($this->extractBaseRn($prototypeCarpet->getRnNumber()) !== $this->extractBaseRn($rnNumber)) {
                    $prototypeCarpet = $this->cloneCarpet($prototypeCarpet, $rnNumber, $imageCommand);
                } else {
                    $prototypeCarpet->setRnNumber($rnNumber);
                }
            }
            if ($prototypeCarpet->getImageCommand() === null) {
                $prototypeCarpet->setImageCommand($imageCommand);
            }

            return $prototypeCarpet;
        }

        $carpet = $this->cloneCarpet($prototypeCarpet, $rnNumber, $imageCommand);
        if ($position === 0) {
            $prototypeCarpet = $carpet;
        }

        return $carpet;
    }

    private function cloneCarpet(?Carpet $prototype, string $rnNumber, ImageCommand $imageCommand): Carpet
    {
        $carpet = new Carpet();
        $carpet->setRnNumber($rnNumber);
        $carpet->setImageCommand($prototype?->getImageCommand() ?? $imageCommand);
        if ($prototype?->getCarpetOrderDetail()) {
            $carpet->setCarpetOrderDetail($prototype->getCarpetOrderDetail());
        }

        $this->entityManager->persist($carpet);

        return $carpet;
    }

    private function extractBaseRn(string $rnNumber): string
    {
        $dashPosition = strpos($rnNumber, '-');

        return $dashPosition === false ? $rnNumber : substr($rnNumber, 0, $dashPosition);
    }

    private function duplicateWorkshopInformation(WorkshopInformation $workshopInformation, int $manufacturerId): WorkshopInformation
    {
        $duplicate = new WorkshopInformation();
        $duplicate->setLaunchDate($workshopInformation->getLaunchDate());
        $duplicate->setExpectedEndDate($workshopInformation->getExpectedEndDate());
        $duplicate->setDateEndAtelierPrev($workshopInformation->getDateEndAtelierPrev());
        $duplicate->setProductionTime($workshopInformation->getProductionTime());

        if (($orderSilk = $workshopInformation->getOrderSilkPercentage()) !== null) {
            $duplicate->setOrderSilkPercentage($orderSilk);
        }
        if (($orderedWidth = $workshopInformation->getOrderedWidth()) !== null) {
            $duplicate->setOrderedWidth($orderedWidth);
        }
        if (($orderedHeight = $workshopInformation->getOrderedHeigh()) !== null) {
            $duplicate->setOrderedHeigh($orderedHeight);
        }
        if (($orderedSurface = $workshopInformation->getOrderedSurface()) !== null) {
            $duplicate->setOrderedSurface($orderedSurface);
        }
        if (($realWidth = $workshopInformation->getRealWidth()) !== null) {
            $duplicate->setRealWidth($realWidth);
        }
        if (($realHeight = $workshopInformation->getRealHeight()) !== null) {
            $duplicate->setRealHeight($realHeight);
        }
        if (($realSurface = $workshopInformation->getRealSurface()) !== null) {
            $duplicate->setRealSurface($realSurface);
        }
        if (($reductionRate = $workshopInformation->getReductionRate()) !== null) {
            $duplicate->setReductionRate($reductionRate);
        }
        $duplicate->setUpcharge($workshopInformation->getUpcharge());
        $duplicate->setCommentUpcharge($workshopInformation->getCommentUpcharge());
        if (($pricePerM2 = $workshopInformation->getCarpetPurchasePricePerM2()) !== null) {
            $duplicate->setCarpetPurchasePricePerM2($pricePerM2);
        }
        $duplicate->setCarpetPurchasePriceCmd($workshopInformation->getCarpetPurchasePriceCmd());
        if (($priceTheoretical = $workshopInformation->getCarpetPurchasePriceTheoretical()) !== null) {
            $duplicate->setCarpetPurchasePriceTheoretical($priceTheoretical);
        }
        if (($priceInvoice = $workshopInformation->getCarpetPurchasePriceInvoice()) !== null) {
            $duplicate->setCarpetPurchasePriceInvoice($priceInvoice);
        }
        $duplicate->setPenalty($workshopInformation->getPenalty());
        $duplicate->setShipping($workshopInformation->getShipping());
        $duplicate->setTva($workshopInformation->getTva());
        $duplicate->setAvailableForSale($workshopInformation->isAvailableForSale());
        $duplicate->setSent($workshopInformation->isSent());
        $duplicate->setReceivedInParis($workshopInformation->isReceivedInParis());
        $duplicate->setSpecialRate($workshopInformation->hasSpecialRate());
        $duplicate->setGrossMargin($workshopInformation->getGrossMargin());
        $duplicate->setReferenceOnInvoice($workshopInformation->getReferenceOnInvoice());
        $duplicate->setInvoiceNumber($workshopInformation->getInvoiceNumber());

        $duplicate->setManufacturerId($manufacturerId);
        $duplicate->setCurrency($workshopInformation->getCurrency());
        $duplicate->setQuality($workshopInformation->getQuality());
        $duplicate->setTarifGroup($workshopInformation->getTarifGroup());

        return $duplicate;
    }

    private function getManufacturerPriceGrid(WorkshopInformation $workshopInformation): ManufacturerPriceGrid
    {
        $manufacturerId = $workshopInformation->getManufacturerId();
        $qualityId = $workshopInformation->getIdQuality();
        $tarifGroupId = $workshopInformation->getIdTarifGroup();

        if ($manufacturerId === null || $qualityId === null || $tarifGroupId === null) {
            throw new RuntimeException('Manufacturer, quality and tarif group are required to resolve manufacturer prices.');
        }

        $priceGrid = $this->manufacturerPriceGridRepository
            ->findOneByManufacturerQualityAndTarifGroup($manufacturerId, $qualityId, $tarifGroupId);

        if (!$priceGrid instanceof ManufacturerPriceGrid) {
            throw new RuntimeException('Manufacturer price grid not found for provided workshop information.');
        }

        return $priceGrid;
    }

    private function createMaterialPurchasePrices(
        $carpetSpec,
        ManufacturerPriceGrid $priceGrid,
        WorkshopInformation $workshopInformation,
        WorkshopOrder $workshopOrder
    ): void
    {
        foreach ($carpetSpec->getMaterials() as $carpetMaterial) {
            $material = $carpetMaterial->getMaterial();
            if (!$material) {
                continue;
            }

            $materialPrice = $this->manufacturerPriceRepository->findOneByGridAndMaterial($priceGrid, $material);
            $price = $materialPrice?->getPrice() ?? '0';

            $mpp = new MaterialPurchasePrice();
            $mpp->setMaterialId($material->getId());
            $mpp->setPrice($price);
            $mpp->setWorkshopInformation($workshopInformation);
            $mpp->setWorkshopOrder($workshopOrder);
            $this->entityManager->persist($mpp);
        }
    }

    private function createWorkshopInformationMaterials(
        $carpetSpec,
        WorkshopInformation $workshopInformation,
        ?ManufacturerPriceGrid $priceGrid = null
    ): void
    {
        foreach ($carpetSpec->getMaterials() as $carpetMaterial) {
            $material = $carpetMaterial->getMaterial();
            if (!$material) {
                continue;
            }

            $workshopInformationMaterial = new WorkshopInformationMaterial();
            $workshopInformationMaterial->setMaterial($material);
            $workshopInformationMaterial->setRate($carpetMaterial->getRate());
            $workshopInformationMaterial->setWorkshopInformation($workshopInformation);

            // Resolve manufacturer material price if price grid available
            $price = '0';
            if ($priceGrid instanceof ManufacturerPriceGrid) {
                $materialPrice = $this->manufacturerPriceRepository->findOneByGridAndMaterial($priceGrid, $material);
                $price = $materialPrice?->getPrice() ?? '0';
            }

            $workshopInformationMaterial->setPrice($price);

            $this->entityManager->persist($workshopInformationMaterial);
        }
    }

    private function formatReference(string $reference, int $position, int $copyCount): string
    {
        if ($copyCount <= 1) {
            return $reference;
        }

        return sprintf('%s-%d', $reference, $position);
    }
}

