<?php

namespace App\Setting\Bus\Command\ManufacturerPriceGrid;

use App\Common\Bus\Command\Command;
use Symfony\Component\Validator\Constraints as Assert;

class CreateManufacturerPriceGridCommand implements Command
{
    public function __construct(
        #[Assert\NotBlank(message: 'Manufacturer ID cannot be empty.')]
        #[Assert\Positive(message: 'Manufacturer ID must be positive.')]
        public readonly int     $manufacturerId,

        #[Assert\NotBlank(message: 'Quality ID cannot be empty.')]
        #[Assert\Positive(message: 'Quality ID must be positive.')]
        public readonly int     $qualityId,

        #[Assert\NotBlank(message: 'Tarif group ID cannot be empty.')]
        #[Assert\Positive(message: 'Tarif group ID must be positive.')]
        public readonly int     $tarifGroupId,

        public readonly ?string $tariffGrid = null,

        #[Assert\PositiveOrZero(message: 'Knots must be positive or zero.')]
        public readonly ?int    $knots = null,

        public readonly ?string $special = null,

        #[Assert\PositiveOrZero(message: 'Standard velours must be positive or zero.')]
        public readonly ?string $standardVelours = null,

        #[Assert\Type(type: 'bool', message: 'Is active must be a boolean.')]
        public readonly bool    $isActive = true,

        /**
         * Optional array of prices: [ { materialId: int, price: string|float, effectiveDate: string } , ... ]
         */
        public readonly ?array  $prices = null
    )
    {
    }

    public function getPrices(): ?array
    {
        return $this->prices;
    }

    public function getManufacturerId(): int
    {
        return $this->manufacturerId;
    }

    public function getQualityId(): int
    {
        return $this->qualityId;
    }

    public function getTarifGroupId(): int
    {
        return $this->tarifGroupId;
    }


    public function getTariffGrid(): ?string
    {
        return $this->tariffGrid;
    }

    public function getKnots(): ?int
    {
        return $this->knots;
    }

    public function getSpecial(): ?string
    {
        return $this->special;
    }

    public function getStandardVelours(): ?string
    {
        return $this->standardVelours;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }
}
