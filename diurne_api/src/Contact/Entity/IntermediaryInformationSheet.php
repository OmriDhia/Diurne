<?php

namespace App\Contact\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class IntermediaryInformationSheet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $saleCondition = null;

    #[ORM\Column(nullable: true)]
    private ?float $comission = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?IntermediaryType $intermediaryType = null;

    #[ORM\OneToOne(inversedBy: 'intermediaryInformationSheet', cascade: ['persist', 'remove'])]
    private ?BankInformationSheet $bankInformationSheet = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSaleCondition(): ?string
    {
        return $this->saleCondition;
    }

    public function setSaleCondition(?string $saleCondition): static
    {
        $this->saleCondition = $saleCondition;

        return $this;
    }

    public function getComission(): ?float
    {
        return $this->comission;
    }

    public function setComission(?float $comission): static
    {
        $this->comission = $comission;

        return $this;
    }

    public function getIntermediaryType(): ?IntermediaryType
    {
        return $this->intermediaryType;
    }

    public function setIntermediaryType(?IntermediaryType $intermediaryType): static
    {
        $this->intermediaryType = $intermediaryType;

        return $this;
    }

    public function getBankInformationSheet(): ?BankInformationSheet
    {
        return $this->bankInformationSheet;
    }

    public function setBankInformationSheet(?BankInformationSheet $bankInformationSheet): static
    {
        $this->bankInformationSheet = $bankInformationSheet;

        return $this;
    }
}
