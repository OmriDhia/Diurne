<?php

namespace App\Contact\Entity;

use App\Setting\Entity\Country;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Address
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column(length: 50)]
    private ?string $fullName = null;
    #[ORM\Column(length: 255)]
    private ?string $address1 = null;

    #[ORM\Column(length: 64)]
    private ?string $city = null;

    #[ORM\Column(length: 12)]
    private ?string $zip_code = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $state = null;

    #[ORM\Column]
    private ?bool $is_f_valide = null;

    #[ORM\Column]
    private ?bool $is_l_valide = null;

    #[ORM\Column]
    private ?bool $is_wrong = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comment = null;

    #[ORM\Column(length: 15, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(length: 15, nullable: true)]
    private ?string $mobile_phone = null;

    #[ORM\ManyToOne(inversedBy: 'addresses')]
    private ?AddressType $addressType = null;

    #[ORM\ManyToOne(targetEntity: Country::class, inversedBy: 'addresses')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Country $country = null;

    #[ORM\ManyToMany(targetEntity: Customer::class, mappedBy: 'addresses')]
    private Collection $customers;

    public function __construct()
    {
        $this->customers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddress1(): ?string
    {
        return $this->address1;
    }

    public function setAddress1(string $address1): static
    {
        $this->address1 = $address1;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zip_code;
    }

    public function setZipCode(string $zip_code): static
    {
        $this->zip_code = $zip_code;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function isIsFValide(): ?bool
    {
        return $this->is_f_valide;
    }

    public function setIsFValide(bool $is_f_valide): static
    {
        $this->is_f_valide = $is_f_valide;

        return $this;
    }

    public function isIsLValide(): ?bool
    {
        return $this->is_l_valide;
    }

    public function setIsLValide(bool $is_l_valide): static
    {
        $this->is_l_valide = $is_l_valide;

        return $this;
    }

    public function isIsWrong(): ?bool
    {
        return $this->is_wrong;
    }

    public function setIsWrong(bool $is_wrong): static
    {
        $this->is_wrong = $is_wrong;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    public function getAddressType(): ?AddressType
    {
        return $this->addressType;
    }

    public function setAddressType(?AddressType $addressType): static
    {
        $this->addressType = $addressType;

        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): static
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): Address
    {
        $this->phone = $phone;

        return $this;
    }

    public function getMobilePhone(): ?string
    {
        return $this->mobile_phone;
    }

    public function setMobilePhone(?string $mobile_phone): Address
    {
        $this->mobile_phone = $mobile_phone;

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
            $customer->addAddress($this);
        }

        return $this;
    }

    public function removeCustomer(Customer $customer): static
    {
        if ($this->customers->removeElement($customer)) {
            $customer->removeAddress($this);
        }

        return $this;
    }

    /**
     * @return ((int|null|string)[]|bool|int|null|string)[]
     *
     * @psalm-return array{address_id: int|null, full_name: null|string, address1: null|string, city: null|string, country: null|string, countryId: int|null, postcode: null|string, state: null|string, phone: null|string, is_f_valide: bool|null, is_l_valide: bool|null, is_wrong: bool|null, comment: null|string, mobile_phone: null|string, addressType: array{addressTypeId: int|null, name: null|string}}
     */
    public function toArray(): array
    {
        return [
            'address_id' => $this->getId(),
            'full_name' => $this->getFullName(),
            'address1' => $this->getAddress1(),
            'city' => $this->getCity(),
            'country' => $this->getCountry()->getName(),
            'countryId' => $this->getCountry()->getId(),
            'postcode' => $this->getZipCode(),
            'state' => $this->getState(),
            'phone' => $this->getPhone(),
            'is_f_valide' => $this->isIsFValide(),
            'is_l_valide' => $this->isIsLValide(),
            'is_wrong' => $this->isIsWrong(),
            'comment' => $this->getComment(),
            'mobile_phone' => $this->getMobilePhone(),
            'addressType' => [
                'addressTypeId' => $this->getAddressType()->getId(),
                'name' => $this->getAddressType()->getName(),
            ],
        ];
    }
}
