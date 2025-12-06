<?php

namespace App\Setting\Entity;

use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Conversion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Currency::class, inversedBy: 'conversions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Currency $currency = null;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $conversionDate = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6)]
    private ?string $coefficient = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }

    public function setCurrency(?Currency $currency): static
    {
        $this->currency = $currency;

        return $this;
    }

    public function getConversionDate(): ?DateTimeImmutable
    {
        return $this->conversionDate;
    }

    public function setConversionDate(?DateTimeImmutable $conversionDate): static
    {
        $this->conversionDate = $conversionDate;

        return $this;
    }

    public function getCoefficient(): ?string
    {
        return $this->coefficient;
    }

    public function setCoefficient(string $coefficient): static
    {
        $this->coefficient = $coefficient;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'currency' => $this->getCurrency() ? $this->getCurrency()->toArray() : null,  // Assuming Currency entity has a toArray() method
            'conversionDate' => $this->getConversionDate() ? $this->getConversionDate()->format('Y-m-d H:i:s') : null,
            'coefficient' => $this->getCoefficient(),
        ];
    }
}
