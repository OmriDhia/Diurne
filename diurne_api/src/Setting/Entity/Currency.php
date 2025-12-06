<?php

namespace App\Setting\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Currency
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'currency', targetEntity: Conversion::class, cascade: ['persist', 'remove'])]
    private $conversions;

    public function __construct()
    {
        $this->conversions = new ArrayCollection();
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

    public function getConversions(): Collection
    {
        return $this->conversions;
    }

    public function addConversion(Conversion $conversion): static
    {
        if (!$this->conversions->contains($conversion)) {
            $this->conversions[] = $conversion;
            $conversion->setCurrency($this);
        }

        return $this;
    }

    public function removeConversion(Conversion $conversion): static
    {
        if ($this->conversions->removeElement($conversion)) {
            if ($conversion->getCurrency() === $this) {
                $conversion->setCurrency(null);
            }
        }

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
        ];
    }
}
