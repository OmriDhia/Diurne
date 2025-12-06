<?php

namespace App\Setting\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class TaxRuleLang
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    private ?Language $language = null;

    #[ORM\Column(length: 50)]
    private ?string $identification = null;

    #[ORM\ManyToOne(inversedBy: 'taxRuleLangs')]
    private ?TaxRule $taxRule = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLanguage(): ?Language
    {
        return $this->language;
    }

    public function setLanguage(?Language $language): static
    {
        $this->language = $language;

        return $this;
    }

    public function getIdentification(): ?string
    {
        return $this->identification;
    }

    public function setIdentification(string $identification): static
    {
        $this->identification = $identification;

        return $this;
    }

    public function getTaxRule(): ?TaxRule
    {
        return $this->taxRule;
    }

    public function setTaxRule(?TaxRule $taxRule): static
    {
        $this->taxRule = $taxRule;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'language' => $this->getLanguage() ? $this->getLanguage()->getId() : null, // assuming Language entity has getId()
            'identification' => $this->getIdentification(),
        ];
    }
}
