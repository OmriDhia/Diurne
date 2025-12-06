<?php

namespace App\Setting\Entity;

use App\Contact\Entity\Address;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Country
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(length: 2)]
    private ?string $iso_code = null;
    #[ORM\Column(length: 50)]
    private ?string $zip_code_format = null;
    #[ORM\OneToMany(mappedBy: 'country', targetEntity: Address::class)]
    private Collection $addresses;
    #[ORM\OneToMany(mappedBy: 'currency', targetEntity: Conversion::class)]
    private Collection $conversions;
    public function __construct()
    {
        $this->addresses = new ArrayCollection();
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

    public function getIsoCode(): ?string
    {
        return $this->iso_code;
    }

    public function setIsoCode(string $iso_code): static
    {
        $this->iso_code = $iso_code;

        return $this;
    }

    public function getZipCodeFormat(): ?string
    {
        return $this->zip_code_format;
    }

    public function setZipCodeFormat(?string $zip_code_format): Country
    {
        $this->zip_code_format = $zip_code_format;

        return $this;
    }
    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    public function addAddress(Address $address): self
    {
        if (!$this->addresses->contains($address)) {
            $this->addresses->add($address);
            $address->setCountry($this);
        }
        return $this;
    }

    public function removeAddress(Address $address): self
    {
        if ($this->addresses->removeElement($address)) {
            if ($address->getCountry() === $this) {
                $address->setCountry(null);
            }
        }
        return $this;
    }
    public function getConversions(): Collection
    {
        return $this->conversions;
    }

    public function addConversion(Conversion $conversion): self
    {
        if (!$this->conversions->contains($conversion)) {
            $this->conversions->add($conversion);
            $conversion->setCurrency($this);
        }
        return $this;
    }

    public function removeConversion(Conversion $conversion): self
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
            'iso_code' => $this->getIsoCode(),
            'zip_code_format' => $this->getZipCodeFormat(),
        ];
    }
}
