<?php

namespace App\Setting\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class TaxRule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6)]
    private ?string $taxRate = null;

    /**
     * @var Collection<int, TaxRuleLang>
     */
    #[ORM\OneToMany(targetEntity: TaxRuleLang::class, mappedBy: 'taxRule')]
    private Collection $taxRuleLangs;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $rate = null;

    public function __construct()
    {
        $this->taxRuleLangs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTaxRate(): ?string
    {
        return $this->taxRate;
    }

    public function setTaxRate(string $taxRate): static
    {
        $this->taxRate = $taxRate;

        return $this;
    }

    /**
     * @return Collection<int, TaxRuleLang>
     */
    public function getTaxRuleLangs(): Collection
    {
        return $this->taxRuleLangs;
    }

    public function addTaxRuleLang(TaxRuleLang $taxRuleLang): static
    {
        if (!$this->taxRuleLangs->contains($taxRuleLang)) {
            $this->taxRuleLangs->add($taxRuleLang);
            $taxRuleLang->setTaxRule($this);
        }

        return $this;
    }

    public function removeTaxRuleLang(TaxRuleLang $taxRuleLang): static
    {
        if ($this->taxRuleLangs->removeElement($taxRuleLang)) {
            // set the owning side to null (unless already changed)
            if ($taxRuleLang->getTaxRule() === $this) {
                $taxRuleLang->setTaxRule(null);
            }
        }

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'taxRate' => $this->getTaxRate(),
            'taxRuleLangs' => array_map(fn ($lang) => $lang->toArray(), $this->taxRuleLangs->toArray()),
        ];
    }

    public function getRate(): ?string
    {
        return $this->rate;
    }

    public function setRate(?string $rate): static
    {
        $this->rate = $rate;

        return $this;
    }
}
