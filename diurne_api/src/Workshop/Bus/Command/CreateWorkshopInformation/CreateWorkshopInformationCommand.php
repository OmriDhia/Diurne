<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Command\CreateWorkshopInformation;

use App\Common\Bus\Command\Command;
use DateTime;

class CreateWorkshopInformationCommand implements Command
{
    /**
     * @param DateTime|null $launchDate
     * @param DateTime|null $expectedEndDate
     * @param DateTime|null $dateEndAtelierPrev
     * @param int $productionTime
     * @param string|null $orderSilkPercentage
     * @param string $orderedWidth
     * @param string $orderedHeigh
     * @param string $orderedSurface
     * @param string $realWidth
     * @param string $realHeight
     * @param string $realSurface
     * @param int $idTarifGroup
     * @param string $reductionRate
     * @param string|null $upcharge
     * @param string|null $commentUpcharge
     * @param string $carpetPurchasePricePerM2
     * @param string|null $carpetPurchasePriceCmd
     * @param string $carpetPurchasePriceTheoretical
     * @param string $carpetPurchasePriceInvoice
     * @param string|null $penalty
     * @param string|null $shipping
     * @param string|null $tva
     * @param bool|null $availableForSale
     * @param bool|null $sent
     * @param bool|null $receivedInParis
     * @param bool|null $specialRate
     * @param string|null $grossMargin
     * @param string|null $referenceOnInvoice
     * @param string|null $invoiceNumber
     * @param int $manufacturerId
     * @param int|null $copy
     * @param int $idQuality
     * @param string $rn
     */
    public function __construct(
        public readonly ?DateTime $launchDate,
        public readonly ?DateTime $expectedEndDate,
        public readonly ?DateTime $dateEndAtelierPrev,
        public readonly int       $productionTime,
        public readonly ?string   $orderSilkPercentage,
        public readonly string    $orderedWidth,
        public readonly string    $orderedHeigh,
        public readonly string    $orderedSurface,
        public readonly string    $realWidth,
        public readonly string    $realHeight,
        public readonly string    $realSurface,
        public readonly int       $idTarifGroup,
        public readonly string    $reductionRate,
        public readonly ?string   $upcharge,
        public readonly ?string   $commentUpcharge,
        public readonly string    $carpetPurchasePricePerM2,
        public readonly ?string   $carpetPurchasePriceCmd,
        public readonly string    $carpetPurchasePriceTheoretical,
        public readonly string    $carpetPurchasePriceInvoice,
        public readonly ?string   $penalty,
        public readonly ?string   $shipping,
        public readonly ?string   $tva,
        public readonly ?bool     $availableForSale,
        public readonly ?bool     $sent,
        public readonly ?bool     $receivedInParis,
        public readonly ?bool     $specialRate,
        public readonly ?string   $grossMargin,
        public readonly ?string   $referenceOnInvoice,
        public readonly ?string   $invoiceNumber,
        public readonly ?int      $currencyId,
        public readonly int       $manufacturerId,
        public readonly ?int      $copy,
        public readonly int       $idQuality,
        public readonly string    $rn
    )
    {
    }

}