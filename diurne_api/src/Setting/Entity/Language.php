<?php

namespace App\Setting\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Contact\Entity\Customer;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
class Language
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(length: 2)]
    private ?string $iso_code = null;
    #[ORM\OneToMany(mappedBy: 'mailingLanguage', targetEntity: Customer::class)]
    private Collection $customers;

    public function __construct()
    {
        $this->customers = new ArrayCollection();
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
    public function getCustomers(): Collection
    {
        return $this->customers;
    }

    public function addCustomer(Customer $customer): self
    {
        if (!$this->customers->contains($customer)) {
            $this->customers->add($customer);
            $customer->setMailingLanguage($this);
        }
        return $this;
    }

    public function removeCustomer(Customer $customer): self
    {
        if ($this->customers->removeElement($customer)) {
            if ($customer->getMailingLanguage() === $this) {
                $customer->setMailingLanguage(null);
            }
        }
        return $this;
    }
    /**
     * @return (int|null|string)[]
     *
     * @psalm-return array{id: int|null, name: null|string, iso_code: null|string}
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'iso_code' => $this->getIsoCode(),
        ];
    }
}
