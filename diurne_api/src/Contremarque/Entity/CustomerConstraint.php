<?php

namespace App\Contremarque\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class CustomerConstraint
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?bool $transmittedPlan = null;

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $libTransmittedPlan = null;

    #[ORM\Column(nullable: true)]
    private ?bool $pit = null;

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $libPit = null;

    #[ORM\Column(nullable: true)]
    private ?bool $lineHeight = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $libLineHeight = null;

    #[ORM\Column(nullable: true)]
    private ?bool $specialThickness = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $libSpecialThickness = null;

    #[ORM\Column(nullable: true)]
    private ?bool $otherCarpetInTheRoom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $libOtherCarpetInTHeRoom = null;

    #[ORM\Column(nullable: true)]
    private ?bool $miniLength = null;

    #[ORM\Column(nullable: true)]
    private ?bool $maxiLength = null;

    #[ORM\Column(nullable: true)]
    private ?bool $miniWidth = null;

    #[ORM\Column(nullable: true)]
    private ?bool $maxiWidth = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $dstWallHeight = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $dstWallDown = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $dstWallLeft = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $dstWallRight = null;

    #[ORM\OneToOne(mappedBy: 'customerConstraint', cascade: ['remove'])]
    private ?CustomerInstruction $customerInstruction = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isTransmittedPlan(): ?bool
    {
        return $this->transmittedPlan;
    }

    public function setTransmittedPlan(?bool $transmittedPlan): static
    {
        $this->transmittedPlan = $transmittedPlan;

        return $this;
    }

    public function getLibTransmittedPlan(): ?string
    {
        return $this->libTransmittedPlan;
    }

    public function setLibTransmittedPlan(?string $libTransmittedPlan): static
    {
        $this->libTransmittedPlan = $libTransmittedPlan;

        return $this;
    }

    public function isPit(): ?bool
    {
        return $this->pit;
    }

    public function setPit(?bool $pit): static
    {
        $this->pit = $pit;

        return $this;
    }

    public function getLibPit(): ?string
    {
        return $this->libPit;
    }

    public function setLibPit(?string $libPit): static
    {
        $this->libPit = $libPit;

        return $this;
    }

    public function isLineHeight(): ?bool
    {
        return $this->lineHeight;
    }

    public function setLineHeight(?bool $lineHeight): static
    {
        $this->lineHeight = $lineHeight;

        return $this;
    }

    public function getLibLineHeight(): ?string
    {
        return $this->libLineHeight;
    }

    public function setLibLineHeight(?string $libLineHeight): static
    {
        $this->libLineHeight = $libLineHeight;

        return $this;
    }

    public function isSpecialThickness(): ?bool
    {
        return $this->specialThickness;
    }

    public function setSpecialThickness(?bool $specialThickness): static
    {
        $this->specialThickness = $specialThickness;

        return $this;
    }

    public function getLibSpecialThickness(): ?string
    {
        return $this->libSpecialThickness;
    }

    public function setLibSpecialThickness(?string $libSpecialThickness): static
    {
        $this->libSpecialThickness = $libSpecialThickness;

        return $this;
    }

    public function isOtherCarpetInTheRoom(): ?bool
    {
        return $this->otherCarpetInTheRoom;
    }

    public function setOtherCarpetInTheRoom(?bool $otherCarpetInTheRoom): static
    {
        $this->otherCarpetInTheRoom = $otherCarpetInTheRoom;

        return $this;
    }

    public function getLibOtherCarpetInTHeRoom(): ?string
    {
        return $this->libOtherCarpetInTHeRoom;
    }

    public function setLibOtherCarpetInTHeRoom(?string $libOtherCarpetInTHeRoom): static
    {
        $this->libOtherCarpetInTHeRoom = $libOtherCarpetInTHeRoom;

        return $this;
    }

    public function isMiniLength(): ?bool
    {
        return $this->miniLength;
    }

    public function setMiniLength(?bool $miniLength): static
    {
        $this->miniLength = $miniLength;

        return $this;
    }

    public function isMaxiLength(): ?bool
    {
        return $this->maxiLength;
    }

    public function setMaxiLength(?bool $maxiLength): static
    {
        $this->maxiLength = $maxiLength;

        return $this;
    }

    public function isMiniWidth(): ?bool
    {
        return $this->miniWidth;
    }

    public function setMiniWidth(?bool $miniWidth): static
    {
        $this->miniWidth = $miniWidth;

        return $this;
    }

    public function isMaxiWidth(): ?bool
    {
        return $this->maxiWidth;
    }

    public function setMaxiWidth(?bool $maxiWidth): static
    {
        $this->maxiWidth = $maxiWidth;

        return $this;
    }

    public function getDstWallHeight(): ?string
    {
        return $this->dstWallHeight;
    }

    public function setDstWallHeight(?string $dstWallHeight): static
    {
        $this->dstWallHeight = $dstWallHeight;

        return $this;
    }

    public function getDstWallDown(): ?string
    {
        return $this->dstWallDown;
    }

    public function setDstWallDown(?string $dstWallDown): static
    {
        $this->dstWallDown = $dstWallDown;

        return $this;
    }

    public function getDstWallLeft(): ?string
    {
        return $this->dstWallLeft;
    }

    public function setDstWallLeft(?string $dstWallLeft): static
    {
        $this->dstWallLeft = $dstWallLeft;

        return $this;
    }

    public function getDstWallRight(): ?string
    {
        return $this->dstWallRight;
    }

    public function setDstWallRight(?string $dstWallRight): static
    {
        $this->dstWallRight = $dstWallRight;

        return $this;
    }

    public function getCustomerInstruction(): ?CustomerInstruction
    {
        return $this->customerInstruction;
    }

    public function setCustomerInstruction(?CustomerInstruction $customerInstruction): static
    {
        // unset the owning side of the relation if necessary
        if (null === $customerInstruction && null !== $this->customerInstruction) {
            $this->customerInstruction->setCustomerConstraint(null);
        }

        // set the owning side of the relation if necessary
        if (null !== $customerInstruction && $customerInstruction->getCustomerConstraint() !== $this) {
            $customerInstruction->setCustomerConstraint($this);
        }

        $this->customerInstruction = $customerInstruction;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'transmittedPlan' => $this->isTransmittedPlan(),
            'libTransmittedPlan' => $this->getLibTransmittedPlan(),
            'pit' => $this->isPit(),
            'libPit' => $this->getLibPit(),
            'lineHeight' => $this->isLineHeight(),
            'libLineHeight' => $this->getLibLineHeight(),
            'specialThickness' => $this->isSpecialThickness(),
            'libSpecialThickness' => $this->getLibSpecialThickness(),
            'otherCarpetInTheRoom' => $this->isOtherCarpetInTheRoom(),
            'libOtherCarpetInTheRoom' => $this->getLibOtherCarpetInTHeRoom(),
            'miniLength' => $this->isMiniLength(),
            'maxiLength' => $this->isMaxiLength(),
            'miniWidth' => $this->isMiniWidth(),
            'maxiWidth' => $this->isMaxiWidth(),
            'dstWallHeight' => $this->getDstWallHeight(),
            'dstWallDown' => $this->getDstWallDown(),
            'dstWallLeft' => $this->getDstWallLeft(),
            'dstWallRight' => $this->getDstWallRight(),
            'customerInstructionId' => $this->getCustomerInstruction()?->getId(), // Optional, returns null if no associated CustomerInstruction
        ];
    }
}
