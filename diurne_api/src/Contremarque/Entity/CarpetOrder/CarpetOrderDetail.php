<?php

namespace App\Contremarque\Entity\CarpetOrder;

use App\Contremarque\Entity\QuoteDetail;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
class CarpetOrderDetail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: CarpetOrder::class, inversedBy: 'carpetOrderDetail')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CarpetOrder $carpetOrder = null; // Changed from $carpet_order_id to $carpetOrder

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?QuoteDetail $quote_detail = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;
    #[ORM\OneToMany(targetEntity: RnAttribution::class, mappedBy: 'carpetOrderDetail')]
    private Collection $rnAttributions;

    public function __construct()
    {
        $this->rnAttributions = new ArrayCollection();
    }

    public function getRnAttributions(): Collection
    {
        return $this->rnAttributions;
    }

    public function setRnAttributions(Collection $rnAttributions): void
    {
        $this->rnAttributions = $rnAttributions;
    }

    public function removeRnAttribution(RnAttribution $rnAttribution): void
    {
        if ($this->rnAttributions->removeElement($rnAttribution)) {
            if ($rnAttribution->getCarpetOrderDetail() === $this) {
                $rnAttribution->setCarpetOrderDetail(null);
            }
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCarpetOrder(): ?CarpetOrder
    {
        return $this->carpetOrder;
    }

    public function setCarpetOrder(?CarpetOrder $carpetOrder): void
    {
        $this->carpetOrder = $carpetOrder;
    }

    public function getQuoteDetail(): ?QuoteDetail
    {
        return $this->quote_detail;
    }

    public function setQuoteDetail(?QuoteDetail $quote_detail): void
    {
        $this->quote_detail = $quote_detail;
    }


    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getQuoteDetailId(): ?QuoteDetail
    {
        return $this->quote_detail;
    }

    public function setQuoteDetailId(?QuoteDetail $quote_detail): void
    {
        $this->quote_detail = $quote_detail;
    }

    public function toArray(): array
    {
        $rnAttributions = [];
        foreach ($this->getRnAttributions() as $attribution) {
            $rnAttributions[] = $attribution->toArray();
        }
        return [
            'id' => $this->id,
            'QuoteDetail' => $this->quote_detail?->toArray(),
            'rnAttributions' => $rnAttributions,
            'carpetOrder' => $this->carpetOrder?->toArray(),
        ];
    }
}
