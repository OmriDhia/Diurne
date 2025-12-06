<?php

declare(strict_types=1);

namespace App\Contact\Bus\Command\Address;

use App\Contact\Bus\Command\Address\ORM\Column;
use App\Common\Bus\Command\Command;

class CreateAddressCommand implements Command
{
    private string $fulName;
    private string $address1;
    private string $city;
    private string $zip_code;
    private ?string $state = null;
    private int $country;
    private int $addressType;
    private ?bool $is_f_valide = null;
    private ?bool $is_l_valide = null;
    private ?bool $is_wrong = null;

    #[Column(type: Types::TEXT, nullable: true)]
    private ?string $comment = null;
    private ?string $phone = null;
    private ?string $mobile_phone = null;

    public function getAddress1(): string
    {
        return $this->address1;
    }

    public function setAddress1(string $address1): CreateAddressCommand
    {
        $this->address1 = $address1;

        return $this;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): CreateAddressCommand
    {
        $this->city = $city;

        return $this;
    }

    public function getZipCode(): string
    {
        return $this->zip_code;
    }

    public function setZipCode(string $zip_code): CreateAddressCommand
    {
        $this->zip_code = $zip_code;

        return $this;
    }

    public function getState(): string|null
    {
        return $this->state;
    }

    /**
     * @param string|null $state
     */
    public function setState($state): CreateAddressCommand
    {
        $this->state = $state;

        return $this;
    }

    public function getCountry(): int
    {
        return $this->country;
    }

    public function setCountry(int $country): CreateAddressCommand
    {
        $this->country = $country;

        return $this;
    }

    public function getAddressType(): int
    {
        return $this->addressType;
    }

    public function setAddressType(int $addressType): CreateAddressCommand
    {
        $this->addressType = $addressType;

        return $this;
    }

    public function getFulName(): string
    {
        return $this->fulName;
    }

    public function setFulName(string $fulName): void
    {
        $this->fulName = $fulName;
    }

    public function getIsFValide(): ?bool
    {
        return $this->is_f_valide;
    }

    public function setIsFValide(?bool $is_f_valide): CreateAddressCommand
    {
        $this->is_f_valide = $is_f_valide;

        return $this;
    }

    public function getIsLValide(): ?bool
    {
        return $this->is_l_valide;
    }

    public function setIsLValide(?bool $is_l_valide): CreateAddressCommand
    {
        $this->is_l_valide = $is_l_valide;

        return $this;
    }

    public function getIsWrong(): ?bool
    {
        return $this->is_wrong;
    }

    public function setIsWrong(?bool $is_wrong): CreateAddressCommand
    {
        $this->is_wrong = $is_wrong;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): CreateAddressCommand
    {
        $this->comment = $comment;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): CreateAddressCommand
    {
        $this->phone = $phone;

        return $this;
    }

    public function getMobilePhone(): ?string
    {
        return $this->mobile_phone;
    }

    public function setMobilePhone(?string $mobile_phone): CreateAddressCommand
    {
        $this->mobile_phone = $mobile_phone;

        return $this;
    }
}
