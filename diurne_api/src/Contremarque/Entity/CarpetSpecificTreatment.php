<?php

namespace App\Contremarque\Entity;

use App\Setting\Entity\SpecialTreatment;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class CarpetSpecificTreatment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'carpetSpecificTreatments')]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private ?QuoteDetail $quoteDetail = null;

    #[ORM\ManyToOne]
    private ?SpecialTreatment $treatment = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $unitPrice = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $totalPrice = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuoteDetail(): ?QuoteDetail
    {
        return $this->quoteDetail;
    }

    public function setQuoteDetail(?QuoteDetail $quoteDetail): static
    {
        $this->quoteDetail = $quoteDetail;

        return $this;
    }

    public function getTreatment(): ?SpecialTreatment
    {
        return $this->treatment;
    }

    public function setTreatment(?SpecialTreatment $treatment): static
    {
        $this->treatment = $treatment;

        return $this;
    }

    public function getUnitPrice(): ?string
    {
        return $this->unitPrice;
    }

    public function setUnitPrice(?string $unitPrice): static
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }

    public function getTotalPrice(): ?string
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(?string $totalPrice): static
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'quoteDetailId' => $this->getQuoteDetail()?->getId(),
            'treatment' => $this->getTreatment()->toArray(),
            'unitPrice' => $this->getUnitPrice(),
            'totalPrice' => $this->getTotalPrice(),
        ];
    }
}
