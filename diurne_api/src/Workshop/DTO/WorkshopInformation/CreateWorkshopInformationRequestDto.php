<?php

declare(strict_types=1);

namespace App\Workshop\DTO\WorkshopInformation;

use App\Common\Assert as CommonAssert;
use App\Common\DTO\BaseDto;
use Symfony\Component\Validator\Constraints as Assert;

class CreateWorkshopInformationRequestDto extends BaseDto
{
    /**
     * @param string $launchDate
     * @param string|null $expectedEndDate
     * @param string|null $orderSilkPercentage
     * @param string $orderedWidth
     * @param string $orderedHeigh
     * @param string $orderedSurface
     * @param string $realWidth
     * @param string $realHeight
     * @param string $realSurface
     * @param int $idTarifGroup
     * @param string $carpetPurchasePricePerM2
     * @param string $carpetPurchasePriceTheoretical
     * @param string $carpetPurchasePriceInvoice
     * @param int $manufacturerId
     * @param int $idQuality
     * @param int $idTarifGroup
     * @param string $Rn
     * @param int|null $copy
     * @param int|null $productionTime
     * @param string|null $reductionRate
     * @param string|null $upcharge
     * @param string|null $commentUpcharge
     * @param string|null $carpetPurchasePriceCmd
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
     * @param int|null $currencyId
     * @param string|null $dateEndAtelierPrev
     */
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\DateTime(format: 'Y-m-d')]
        public readonly string  $launchDate,


        #[Assert\DateTime(format: 'Y-m-d H:i:s')]
        public readonly ?string $expectedEndDate = null,

        #[CommonAssert\DecimalPrecision(precision: 20, scale: 6)]
        public ?string          $orderSilkPercentage = null,

        #[Assert\NotBlank]
        #[CommonAssert\DecimalPrecision(precision: 20, scale: 6)]
        public string           $orderedWidth,

        #[Assert\NotBlank]
        #[CommonAssert\DecimalPrecision(precision: 20, scale: 6)]
        public string           $orderedHeigh,

        #[Assert\NotBlank]
        #[CommonAssert\DecimalPrecision(precision: 20, scale: 6)]
        public string           $orderedSurface,

        #[Assert\NotBlank]
        #[CommonAssert\DecimalPrecision(precision: 20, scale: 6)]
        public string           $realWidth,

        #[Assert\NotBlank]
        #[CommonAssert\DecimalPrecision(precision: 20, scale: 6)]
        public string           $realHeight,

        #[Assert\NotBlank]
        #[CommonAssert\DecimalPrecision(precision: 20, scale: 6)]
        public string           $realSurface,

        // Integer Fields
        #[Assert\NotBlank]
        #[Assert\Positive]
        public int              $idTarifGroup,
        // Price Fields
        #[Assert\NotBlank]
        #[CommonAssert\DecimalPrecision(precision: 20, scale: 6)]
        public string           $carpetPurchasePricePerM2,
        #[Assert\NotBlank]
        #[CommonAssert\DecimalPrecision(precision: 20, scale: 6)]
        public string           $carpetPurchasePriceTheoretical,
        #[Assert\NotBlank]
        #[CommonAssert\DecimalPrecision(precision: 20, scale: 6)]
        public string           $carpetPurchasePriceInvoice,
        // Required IDs
        #[Assert\NotBlank]
        #[Assert\Positive]
        public int              $manufacturerId,
        #[Assert\NotBlank]
        #[Assert\Positive]
        public int              $idQuality,

        #[Assert\NotBlank]
        #[Assert\Length(min: 1, max: 50)]
        public string           $Rn,

        #[Assert\Positive]
        public ?int             $copy = 1,
        #[Assert\Type(type: 'integer')]
        public ?int             $productionTime = null,

        // Nullable Decimal Fields
        #[CommonAssert\DecimalPrecision(precision: 20, scale: 6)]
        public ?string          $reductionRate = null,

        #[CommonAssert\DecimalPrecision(precision: 20, scale: 6)]
        public ?string          $upcharge = null,

        #[Assert\Length(max: 50)]
        public ?string          $commentUpcharge = null,

        #[CommonAssert\DecimalPrecision(precision: 20, scale: 6)]
        public ?string          $carpetPurchasePriceCmd = null,

        // Optional Fields
        #[CommonAssert\DecimalPrecision(precision: 20, scale: 6)]
        public ?string          $penalty = null,

        #[CommonAssert\DecimalPrecision(precision: 20, scale: 6)]
        public ?string          $shipping = null,

        #[CommonAssert\DecimalPrecision(precision: 20, scale: 6)]
        public ?string          $tva = null,

        public ?bool            $availableForSale = null,

        public ?bool            $sent = null,

        public ?bool            $receivedInParis = null,

        public ?bool            $specialRate = null,

        #[CommonAssert\DecimalPrecision(precision: 20, scale: 6)]
        public ?string          $grossMargin = null,

        // String Fields
        #[Assert\Length(max: 50)]
        public ?string          $referenceOnInvoice = null,

        #[Assert\Length(max: 50)]
        public ?string          $invoiceNumber = null,

        #[Assert\Positive]
        public ?int             $currencyId = null,

        #[Assert\DateTime(format: 'Y-m-d')]
        public readonly ?string $dateEndAtelierPrev = null
    )
    {
    }
}