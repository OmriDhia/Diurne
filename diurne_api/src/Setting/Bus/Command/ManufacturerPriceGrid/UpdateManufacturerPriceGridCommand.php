<?php

namespace App\Setting\Bus\Command\ManufacturerPriceGrid;

use App\Common\Bus\Command\Command;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateManufacturerPriceGridCommand implements Command
{
    public function __construct(
        #[Assert\NotBlank(message: 'ID cannot be empty.')]
        #[Assert\Positive(message: 'ID must be positive.')]
        public readonly int     $id,

        #[Assert\Positive(message: 'Manufacturer ID must be positive.')]
        public readonly ?int    $manufacturerId = null,

        #[Assert\Positive(message: 'Tarif group ID must be positive.')]
        public readonly ?int    $tarifGroupId = null,

        public readonly ?string $tariffGrid = null,

        #[Assert\PositiveOrZero(message: 'Knots must be positive or zero.')]
        public readonly ?int    $knots = null,

        public readonly ?string $special = null,

        #[Assert\PositiveOrZero(message: 'Standard velours must be positive or zero.')]
        public readonly ?string $standardVelours = null,

        #[Assert\Type(type: 'bool', message: 'Is active must be a boolean.')]
        public readonly bool    $isActive = true,

        /** Optional array of prices: [ { materialId: int, price: string|float, effectiveDate: string } , ... ] */
        public readonly ?array  $prices = null,
    )
    {
    }

    public function getPrices(): ?array
    {
        return $this->prices;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getManufacturerId(): ?int
    {
        return $this->manufacturerId;
    }

    public function getTarifGroupId(): ?int
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

    public function isActive(): ?bool
    {
        return $this->isActive;
    }
}
