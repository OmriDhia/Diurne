<?php

namespace App\Contremarque\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Finishing
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $fabricColor = null;

    #[ORM\Column(nullable: true)]
    private ?bool $fringe = null;

    #[ORM\Column(nullable: true)]
    private ?bool $withoutBanking = null;

    #[ORM\Column(nullable: true)]
    private ?bool $noBinding = null;

    #[ORM\Column(nullable: true)]
    private ?bool $mzCarved = null;

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $otherCarvedSignature = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $standardVelvetHeight = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $specialVelvetHeight = null;

    #[ORM\OneToOne(mappedBy: 'finitionInstruction', targetEntity: CustomerInstruction::class)]
    private ?CustomerInstruction $customerInstruction = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFabricColor(): ?string
    {
        return $this->fabricColor;
    }

    public function setFabricColor(?string $fabricColor): static
    {
        $this->fabricColor = $fabricColor;

        return $this;
    }

    public function isFringe(): ?bool
    {
        return $this->fringe;
    }

    public function setFringe(?bool $fringe): static
    {
        $this->fringe = $fringe;

        return $this;
    }

    public function isWithoutBanking(): ?bool
    {
        return $this->withoutBanking;
    }

    public function setWithoutBanking(?bool $withoutBanking): static
    {
        $this->withoutBanking = $withoutBanking;

        return $this;
    }

    public function isNoBinding(): ?bool
    {
        return $this->noBinding;
    }

    public function setNoBinding(?bool $noBinding): static
    {
        $this->noBinding = $noBinding;

        return $this;
    }

    public function isMzCarved(): ?bool
    {
        return $this->mzCarved;
    }

    public function setMzCarved(?bool $mzCarved): static
    {
        $this->mzCarved = $mzCarved;

        return $this;
    }

    public function getOtherCarvedSignature(): ?string
    {
        return $this->otherCarvedSignature;
    }

    public function setOtherCarvedSignature(?string $otherCarvedSignature): static
    {
        $this->otherCarvedSignature = $otherCarvedSignature;

        return $this;
    }

    public function getStandardVelvetHeight(): ?string
    {
        return $this->standardVelvetHeight;
    }

    public function setStandardVelvetHeight(?string $standardVelvetHeight): static
    {
        $this->standardVelvetHeight = $standardVelvetHeight;

        return $this;
    }

    public function getSpecialVelvetHeight(): ?string
    {
        return $this->specialVelvetHeight;
    }

    public function setSpecialVelvetHeight(?string $specialVelvetHeight): static
    {
        $this->specialVelvetHeight = $specialVelvetHeight;

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
            $this->customerInstruction->setFinitionInstruction(null);
        }

        // set the owning side of the relation if necessary
        if (null !== $customerInstruction && $customerInstruction->getFinitionInstruction() !== $this) {
            $customerInstruction->setFinitionInstruction($this);
        }

        $this->customerInstruction = $customerInstruction;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'fabricColor' => $this->fabricColor,
            'fringe' => $this->fringe,
            'withoutBanking' => $this->withoutBanking,
            'noBinding' => $this->noBinding,
            'mzCarved' => $this->mzCarved,
            'otherCarvedSignature' => $this->otherCarvedSignature,
            'standardVelvetHeight' => $this->standardVelvetHeight,
            'specialVelvetHeight' => $this->specialVelvetHeight,
            'customerInstructionId' => $this->customerInstruction?->getId(),
        ];
    }
}
