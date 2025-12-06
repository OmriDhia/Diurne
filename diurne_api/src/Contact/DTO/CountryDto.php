<?php

declare(strict_types=1);

namespace App\Contact\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CountryDto
{
    #[Assert\NotBlank]
    #[Assert\Length(max: 50)]
    private ?string $name = null;

    #[Assert\NotBlank]
    #[Assert\Length(max: 2)]
    private string $iso_code;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getIsoCode(): ?string
    {
        return $this->iso_code;
    }

    public function setIsoCode(string $iso_code): void
    {
        $this->iso_code = $iso_code;
    }
}
