<?php

namespace App\Contremarque\Entity;

use App\Setting\Entity\PriceType;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

// Import for CarpetPriceSimulator

#[ORM\Entity]
class CarpetPriceBase
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: PriceType::class, cascade: ['persist'])]
    private ?PriceType $priceType = null;

    #[ORM\ManyToOne(targetEntity: QuoteDetail::class, cascade: ['persist', 'remove'], inversedBy: 'carpetPriceBases')]
    #[ORM\JoinColumn(name: 'quote_detail_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private ?QuoteDetail $quoteDetail = null;

    #[ORM\Column(type: 'decimal', precision: 20, scale: 6, nullable: true)]
    private ?string $totalPriceHt = null;

    #[ORM\Column(type: 'decimal', precision: 20, scale: 6, nullable: true)]
    private ?string $totalPriceTtc = null;

    #[ORM\OneToMany(targetEntity: CarpetPriceSimulator::class, mappedBy: 'basePrice', cascade: ['persist', 'remove'])]
    private $priceSimulators;

    public function __construct()
    {
        $this->priceSimulators = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getPriceType(): ?PriceType
    {
        return $this->priceType;
    }

    public function setPriceType(?PriceType $priceType): self
    {
        $this->priceType = $priceType;

        return $this;
    }

    public function getQuoteDetail(): ?QuoteDetail
    {
        return $this->quoteDetail;
    }

    public function setQuoteDetail(?QuoteDetail $quoteDetail): self
    {
        $this->quoteDetail = $quoteDetail;
        return $this;
    }

    public function getTotalPriceHt(): ?string
    {
        return $this->totalPriceHt;
    }

    public function setTotalPriceHt(?string $totalPriceHt): self
    {
        $this->totalPriceHt = $totalPriceHt;

        return $this;
    }

    public function getTotalPriceTtc(): ?string
    {
        return $this->totalPriceTtc;
    }

    public function setTotalPriceTtc(?string $totalPriceTtc): self
    {
        $this->totalPriceTtc = $totalPriceTtc;

        return $this;
    }

    public function getPriceSimulators()
    {
        return $this->priceSimulators;
    }
}
