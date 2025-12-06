<?php

namespace App\Workshop\DTO\WorkshopInformation;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateWorkshopInformationRequestDto
{
    /**
     * @param string|null $launchDate
     * @param string|null $expectedEndDate
     * @param string|null $dateEndAtelierPrev
     * @param int|null $productionTime
     * @param string|null $orderSilkPercentage
     * @param string|null $orderedWidth
     * @param string|null $orderedHeigh
     * @param string|null $orderedSurface
     * @param string|null $realWidth
     * @param string|null $realHeight
     * @param string|null $realSurface
     * @param int|null $idTarifGroup
     * @param string|null $reductionRate
     * @param string|null $upcharge
     * @param string|null $commentUpcharge
     * @param string|null $carpetPurchasePricePerM2
     * @param string|null $carpetPurchasePriceCmd
     * @param string|null $carpetPurchasePriceTheoretical
     * @param string|null $carpetPurchasePriceInvoice
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
     * @param int|null $manufacturerId
     * @param int|null $idQuality
     * @param int|null $idTarifGroup
     * @param string|null $Rn
     * @param int|null $copy
     */
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\DateTime(format: 'Y-m-d')]
        public readonly ?string $launchDate,

        #[Assert\DateTime(format: 'Y-m-d H:i:s')]
        public readonly ?string $expectedEndDate = null,
        #[Assert\NotBlank]
        #[Assert\DateTime(format: 'Y-m-d')]
        public readonly ?string $dateEndAtelierPrev,

        #[Assert\Positive]
        public readonly ?int    $productionTime,

        #[Assert\Type('string')]
        public readonly ?string $orderSilkPercentage,

        #[Assert\NotBlank]
        #[Assert\Type('string')]
        public readonly ?string $orderedWidth,

        #[Assert\NotBlank]
        #[Assert\Type('string')]
        public readonly ?string $orderedHeigh,

        #[Assert\NotBlank]
        #[Assert\Type('string')]
        public readonly ?string $orderedSurface,

        #[Assert\NotBlank]
        #[Assert\Type('string')]
        public readonly ?string $realWidth,

        #[Assert\NotBlank]
        #[Assert\Type('string')]
        public readonly ?string $realHeight,

        #[Assert\NotBlank]
        #[Assert\Type('string')]
        public readonly ?string $realSurface,

        #[Assert\NotBlank]
        #[Assert\Type('string')]
        public readonly ?string $reductionRate,

        #[Assert\Type('string')]
        public readonly ?string $upcharge,

        #[Assert\Type('string')]
        #[Assert\Length(max: 50)]
        public readonly ?string $commentUpcharge,

        #[Assert\NotBlank]
        #[Assert\Type('string')]
        public readonly ?string $carpetPurchasePricePerM2,

        #[Assert\Type('string')]
        public readonly ?string $carpetPurchasePriceCmd,

        #[Assert\NotBlank]
        #[Assert\Type('string')]
        public readonly ?string $carpetPurchasePriceTheoretical,

        #[Assert\NotBlank]
        #[Assert\Type('string')]
        public readonly ?string $carpetPurchasePriceInvoice,

        #[Assert\Type('string')]
        public readonly ?string $penalty,

        #[Assert\Type('string')]
        public readonly ?string $shipping,

        #[Assert\Type('string')]
        public readonly ?string $tva,

        #[Assert\Type('bool')]
        public readonly ?bool   $availableForSale,

        #[Assert\Type('bool')]
        public readonly ?bool   $sent,

        #[Assert\Type('bool')]
        public readonly ?bool   $receivedInParis,

        #[Assert\Type('bool')]
        public readonly ?bool   $specialRate,

        #[Assert\Type('string')]
        public readonly ?string $grossMargin,

        #[Assert\Type('string')]
        #[Assert\Length(max: 50)]
        public readonly ?string $referenceOnInvoice,

        #[Assert\Type('string')]
        #[Assert\Length(max: 50)]
        public readonly ?string $invoiceNumber,

        #[Assert\Type('int')]
        public readonly ?int    $currencyId,

        #[Assert\NotBlank]
        #[Assert\Type('int')]
        public readonly ?int    $manufacturerId,

        #[Assert\Type('int')]
        public readonly ?int    $idQuality,

        #[Assert\Type('int')]
        public readonly ?int    $idTarifGroup,

        #[Assert\NotBlank]
        #[Assert\Type('string')]
        #[Assert\Length(max: 50)]
        public readonly ?string $Rn,

        #[Assert\Positive]
        public readonly ?int    $copy = null
    )
    {
    }
}
