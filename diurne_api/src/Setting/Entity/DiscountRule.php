<?php

namespace App\Setting\Entity;

use App\Contact\Entity\Customer;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class DiscountRule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $discount = null;

    #[ORM\OneToMany(targetEntity: DiscountRuleLang::class, mappedBy: 'discountRule')]
    private Collection $discountRuleLangs;

    #[ORM\OneToMany(targetEntity: Customer::class, mappedBy: 'discountRule')]
    private Collection $customers;

    public function __construct()
    {
        $this->discountRuleLangs = new ArrayCollection();
        $this->customers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDiscount(): ?string
    {
        return $this->discount;
    }

    public function setDiscount(string $discount): static
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * @return Collection<int, DiscountRuleLang>
     */
    public function getDiscountRuleLangs(): Collection
    {
        return $this->discountRuleLangs;
    }

    public function addDiscountRuleLang(DiscountRuleLang $discountRuleLang): static
    {
        if (!$this->discountRuleLangs->contains($discountRuleLang)) {
            $this->discountRuleLangs->add($discountRuleLang);
            $discountRuleLang->setDiscountRule($this);
        }

        return $this;
    }

    public function removeDiscountRuleLang(DiscountRuleLang $discountRuleLang): static
    {
        if ($this->discountRuleLangs->removeElement($discountRuleLang)) {
            // set the owning side to null (unless already changed)
            if ($discountRuleLang->getDiscountRule() === $this) {
                $discountRuleLang->setDiscountRule(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Customer>
     */
    public function getCustomers(): Collection
    {
        return $this->customers;
    }

    public function addCustomer(Customer $customer): static
    {
        if (!$this->customers->contains($customer)) {
            $this->customers->add($customer);
            $customer->setDiscountRule($this);
        }

        return $this;
    }

    public function removeCustomer(Customer $customer): static
    {
        if ($this->customers->removeElement($customer)) {
            // set the owning side to null (unless already changed)
            if ($customer->getDiscountRule() === $this) {
                $customer->setDiscountRule(null);
            }
        }

        return $this;
    }

    /**
     * @return ((null|string)[]|int|null|string)[]
     *
     * @psalm-return array{discount_rule_id: int|null, discount: null|string, labels: list{0?: null|string,...}}
     */
    public function toArray(): array
    {
        $labels = [];
        foreach ($this->getDiscountRuleLangs() as $discountRuleLang) {
            $labels[] = $discountRuleLang->getLabel();
        }

        return [
            'discount_rule_id' => $this->getId(),
            'discount' => $this->getDiscount(),
            'labels' => $labels,
        ];
    }
}
