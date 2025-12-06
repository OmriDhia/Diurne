<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\UpdateConstraint;

use App\Common\Bus\Command\Command;

class UpdateConstraintCommand implements Command
{
    public function __construct(private readonly int $customerInstructionId, private readonly int $constraintId, private readonly ?bool $transmittedPlan, private readonly ?string $libTransmittedPlan, private readonly ?bool $pit, private readonly ?string $libPit, private readonly ?bool $lineHeight, private readonly ?string $libLineHeight, private readonly ?bool $specialThickness, private readonly ?string $libSpecialThickness, private readonly ?bool $otherCarpetInTheRoom, private readonly ?string $libOtherCarpetInTheRoom, private readonly ?bool $miniLength, private readonly ?bool $maxiLength, private readonly ?bool $miniWidth, private readonly ?bool $maxiWidth, private readonly ?string $dstWallHeight, private readonly ?string $dstWallDown, private readonly ?string $dstWallLeft, private readonly ?string $dstWallRight)
    {
    }

    public function getCustomerInstructionId(): int
    {
        return $this->customerInstructionId;
    }

    public function getConstraintId(): int
    {
        return $this->constraintId;
    }

    public function getTransmittedPlan(): ?bool
    {
        return $this->transmittedPlan;
    }

    public function getLibTransmittedPlan(): ?string
    {
        return $this->libTransmittedPlan;
    }

    public function getPit(): ?bool
    {
        return $this->pit;
    }

    public function getLibPit(): ?string
    {
        return $this->libPit;
    }

    public function getLineHeight(): ?bool
    {
        return $this->lineHeight;
    }

    public function getLibLineHeight(): ?string
    {
        return $this->libLineHeight;
    }

    public function getSpecialThickness(): ?bool
    {
        return $this->specialThickness;
    }

    public function getLibSpecialThickness(): ?string
    {
        return $this->libSpecialThickness;
    }

    public function getOtherCarpetInTheRoom(): ?bool
    {
        return $this->otherCarpetInTheRoom;
    }

    public function getLibOtherCarpetInTheRoom(): ?string
    {
        return $this->libOtherCarpetInTheRoom;
    }

    public function getMiniLength(): ?bool
    {
        return $this->miniLength;
    }

    public function getMaxiLength(): ?bool
    {
        return $this->maxiLength;
    }

    public function getMiniWidth(): ?bool
    {
        return $this->miniWidth;
    }

    public function getMaxiWidth(): ?bool
    {
        return $this->maxiWidth;
    }

    public function getDstWallHeight(): ?string
    {
        return $this->dstWallHeight;
    }

    public function getDstWallDown(): ?string
    {
        return $this->dstWallDown;
    }

    public function getDstWallLeft(): ?string
    {
        return $this->dstWallLeft;
    }

    public function getDstWallRight(): ?string
    {
        return $this->dstWallRight;
    }
}
