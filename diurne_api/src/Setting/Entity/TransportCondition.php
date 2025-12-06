<?php

namespace App\Setting\Entity;

use App\Contremarque\Entity\Quote;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
class TransportCondition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 70)]
    private ?string $name = null;

    /**
     * @var Collection<int, TransportConditionLang>
     */
    #[ORM\OneToMany(targetEntity: TransportConditionLang::class, mappedBy: 'transportCondition', orphanRemoval: true)]
    private Collection $transportConditionLangs;

    /**
     * @var Collection<int, Quote>
     */
    #[ORM\OneToMany(targetEntity: Quote::class, mappedBy: 'transportCondition')]
    private Collection $quotes;

    public function __construct()
    {
        $this->transportConditionLangs = new ArrayCollection();
        $this->quotes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, TransportConditionLang>
     */
    public function getTransportConditionLangs(): Collection
    {
        return $this->transportConditionLangs;
    }

    public function addTransportConditionLang(TransportConditionLang $transportConditionLang): static
    {
        if (!$this->transportConditionLangs->contains($transportConditionLang)) {
            $this->transportConditionLangs->add($transportConditionLang);
            $transportConditionLang->setTransportCondition($this);
        }

        return $this;
    }

    public function removeTransportConditionLang(TransportConditionLang $transportConditionLang): static
    {
        if ($this->transportConditionLangs->removeElement($transportConditionLang)) {
            // set the owning side to null (unless already changed)
            if ($transportConditionLang->getTransportCondition() === $this) {
                $transportConditionLang->setTransportCondition(null);
            }
        }

        return $this;
    }

    /**
     * @return (array[]|int|null|string)[]
     *
     * @psalm-return array{id: int|null, name: null|string, languages: array<int, array>}
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'languages' => $this->getTransportConditionLangs()->map(fn (TransportConditionLang $lang) => $lang->toArray())->toArray(),
        ];
    }

    /**
     * @return Collection<int, Quote>
     */
    public function getQuotes(): Collection
    {
        return $this->quotes;
    }

    public function addQuote(Quote $quote): static
    {
        if (!$this->quotes->contains($quote)) {
            $this->quotes->add($quote);
            $quote->setTransportCondition($this);
        }

        return $this;
    }

    public function removeQuote(Quote $quote): static
    {
        if ($this->quotes->removeElement($quote)) {
            // set the owning side to null (unless already changed)
            if ($quote->getTransportCondition() === $this) {
                $quote->setTransportCondition(null);
            }
        }

        return $this;
    }
}
