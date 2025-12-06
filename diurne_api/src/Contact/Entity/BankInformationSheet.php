<?php

namespace App\Contact\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class BankInformationSheet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $bank_name = null;
    #[ORM\Column(length: 255)]
    private ?string $iban = null;

    #[ORM\Column(length: 255)]
    private ?string $swift_code = null;

    #[ORM\OneToOne(mappedBy: 'bankInformationSheet', cascade: ['persist', 'remove'])]
    private ?IntermediaryInformationSheet $intermediaryInformationSheet = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBankName(): ?string
    {
        return $this->bank_name;
    }

    public function setBankName(string $bank_name): static
    {
        $this->bank_name = $bank_name;

        return $this;
    }

    public function getIban(): ?string
    {
        return $this->iban;
    }

    public function setIban(string $iban): static
    {
        $this->iban = $iban;

        return $this;
    }

    public function getSwiftCode(): ?string
    {
        return $this->swift_code;
    }

    public function setSwiftCode(string $swift_code): static
    {
        $this->swift_code = $swift_code;

        return $this;
    }

    public function getIntermediaryInformationSheet(): ?IntermediaryInformationSheet
    {
        return $this->intermediaryInformationSheet;
    }

    public function setIntermediaryInformationSheet(?IntermediaryInformationSheet $intermediaryInformationSheet): static
    {
        // unset the owning side of the relation if necessary
        if (null === $intermediaryInformationSheet && null !== $this->intermediaryInformationSheet) {
            $this->intermediaryInformationSheet->setBankInformationSheet(null);
        }

        // set the owning side of the relation if necessary
        if (null !== $intermediaryInformationSheet && $intermediaryInformationSheet->getBankInformationSheet() !== $this) {
            $intermediaryInformationSheet->setBankInformationSheet($this);
        }

        $this->intermediaryInformationSheet = $intermediaryInformationSheet;

        return $this;
    }
}
