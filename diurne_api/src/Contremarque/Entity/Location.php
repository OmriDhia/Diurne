<?php

namespace App\Contremarque\Entity;

use DateTimeInterface;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Index(name: 'idx_location_carpet_type_description', columns: ['carpet_type_id', 'description'])]
class Location
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    private ?CarpetType $carpetType = null;

    #[ORM\Column(length: 50)]
    private ?string $description = null;

    #[ORM\Column]
    private ?bool $quote_processed = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $quote_processing_date = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $price_min = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $price_max = null;

    #[ORM\Column]
    private ?DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?DateTimeImmutable $updated_at = null;

    #[ORM\ManyToOne(inversedBy: 'locations')]
    private ?Contremarque $contremarque = null;

    /**
     * @var Collection<int, CarpetReference>
     */
    #[ORM\OneToMany(targetEntity: CarpetReference::class, mappedBy: 'location', orphanRemoval: true)]
    private Collection $carpetReference;

    #[ORM\OneToMany(mappedBy: 'location', targetEntity: Sample::class)]
    private Collection $samples;

    #[ORM\OneToMany(mappedBy: 'location', targetEntity: CarpetDesignOrder::class)]
    private Collection $carpetDesignOrders;

    #[ORM\OneToMany(mappedBy: 'location', targetEntity: QuoteDetail::class)]
    private Collection $quoteDetails;

    public function __construct()
    {
        $this->carpetReference = new ArrayCollection();
        $this->carpetDesignOrders = new ArrayCollection();
        $this->quoteDetails = new ArrayCollection();
        $this->created_at = new DateTimeImmutable();
        $this->updated_at = new DateTimeImmutable();

        $this->samples = new ArrayCollection();
    }

    public function getSamples(): Collection
    {
        return $this->samples;
    }

    public function addSample(Sample $sample): self
    {
        if (!$this->samples->contains($sample)) {
            $this->samples[] = $sample;
            $sample->setLocation($this);
        }
        return $this;
    }

    public function removeSample(Sample $sample): self
    {
        if ($this->samples->removeElement($sample)) {
            if ($sample->getLocation() === $this) {
                $sample->setLocation(null);
            }
        }
        return $this;
    }



    public function getId(): ?int
    {
        return $this->id;
    }



    public function getCarpetType(): ?CarpetType
    {
        return $this->carpetType;
    }

    public function setCarpetType(?CarpetType $carpetType): static
    {
        $this->carpetType = $carpetType;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function isQuoteProcessed(): ?bool
    {
        return $this->quote_processed;
    }

    public function setQuoteProcessed(?bool $quote_processed): static
    {
        $this->quote_processed = $quote_processed;
        return $this;
    }

    public function getQuoteProcessingDate(): ?DateTimeInterface
    {
        return $this->quote_processing_date;
    }

    public function setQuoteProcessingDate(?DateTimeInterface $quote_processing_date): static
    {
        $this->quote_processing_date = $quote_processing_date;

        return $this;
    }

    public function getPriceMin(): ?string
    {
        return $this->price_min;
    }

    public function setPriceMin(?string $price_min): static
    {
        $this->price_min = $price_min;

        return $this;
    }

    public function getPriceMax(): ?string
    {
        return $this->price_max;
    }

    public function setPriceMax(?string $price_max): static
    {
        $this->price_max = $price_max;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getContremarque(): ?Contremarque
    {
        return $this->contremarque;
    }

    public function setContremarque(?Contremarque $contremarque): static
    {
        $this->contremarque = $contremarque;
        return $this;
    }

    /**
     * @return Collection<int, CarpetReference>
     */
    public function getCarpetReference(): Collection
    {
        return $this->carpetReference;
    }

    public function addCarpetReference(CarpetReference $carpetReference): static
    {
        if (!$this->carpetReference->contains($carpetReference)) {
            $this->carpetReference->add($carpetReference);
            $carpetReference->setLocation($this);
        }

        return $this;
    }

    public function removeCarpetReference(CarpetReference $carpetReference): static
    {
        if ($this->carpetReference->removeElement($carpetReference)) {
            // set the owning side to null (unless already changed)
            if ($carpetReference->getLocation() === $this) {
                $carpetReference->setLocation(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, CarpetDesignOrder>
     */
    public function getCarpetDesignOrders(): Collection
    {
        return $this->carpetDesignOrders;
    }

    public function addCarpetDesignOrder(CarpetDesignOrder $carpetDesignOrder): static
    {
        if (!$this->carpetDesignOrders->contains($carpetDesignOrder)) {
            $this->carpetDesignOrders->add($carpetDesignOrder);
            $carpetDesignOrder->setLocation($this);
        }
        return $this;
    }

    public function removeCarpetDesignOrder(CarpetDesignOrder $carpetDesignOrder): static
    {
        if ($this->carpetDesignOrders->removeElement($carpetDesignOrder)) {
            if ($carpetDesignOrder->getLocation() === $this) {
                $carpetDesignOrder->setLocation(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, QuoteDetail>
     */
    public function getQuoteDetails(): Collection
    {
        return $this->quoteDetails;
    }

    public function addQuoteDetail(QuoteDetail $quoteDetail): static
    {
        if (!$this->quoteDetails->contains($quoteDetail)) {
            $this->quoteDetails->add($quoteDetail);
            $quoteDetail->setLocation($this);
        }
        return $this;
    }

    public function removeQuoteDetail(QuoteDetail $quoteDetail): static
    {
        if ($this->quoteDetails->removeElement($quoteDetail)) {
            if ($quoteDetail->getLocation() === $this) {
                $quoteDetail->setLocation(null);
            }
        }
        return $this;
    }

    public function toArray(): array
    {
        return [
            'location_id' => $this->getId(),
            'contremarque_id' => $this->getContremarque() ? $this->getContremarque()->getId() : '',
            'carpetType_id' => $this->getCarpetType() ? $this->getCarpetType()->getId() : '',
            'description' => $this->getDescription(),
            'quote_processed' => $this->isQuoteProcessed(),
            'quote_processing_date' => $this->getQuoteProcessingDate(),
            'price_min' => $this->getPriceMin(),
            'price_max' => $this->getPriceMax(),
            'created_at' => $this->getCreatedAt(),
            'updated_at' => $this->getUpdatedAt(),
        ];
    }
}
