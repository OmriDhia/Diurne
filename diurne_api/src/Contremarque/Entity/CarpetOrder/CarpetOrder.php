<?php

namespace App\Contremarque\Entity\CarpetOrder;

use App\Contremarque\Entity\Contremarque;
use App\Contremarque\Entity\Quote;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
class CarpetOrder
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $reference = null;

    #[ORM\ManyToOne(targetEntity: Quote::class)]
    #[ORM\JoinColumn(name: 'original_quote_id', referencedColumnName: 'id')]
    private ?Quote $originalQuote = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Quote $clonedQuote = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Contremarque $contremarque = null;
    #[ORM\OneToMany (targetEntity: CarpetOrderDetail::class, mappedBy: 'carpetOrder')]
    private Collection $carpetOrderDetail;

    #[ORM\Column(nullable: true)]
    private ?int $commercialId = null;

    public function __construct()
    {
        $this->carpetOrderDetail = new ArrayCollection();
    }

    public function getContremarqueId(): ?Contremarque
    {
        return $this->contremarque;
    }

    public function setContremarqueId(?Contremarque $contremarque): void
    {
        $this->contremarque = $contremarque;
    }

    public function getCommercialId(): ?int
    {
        return $this->commercialId;
    }

    public function setCommercialId(?int $commercialId): void
    {
        $this->commercialId = $commercialId;
    }

    public function getCurrentCommercialId(): ?int
    {
        $contremarque = $this->getContremarqueId();
        if (!$contremarque) {
            return null;
        }

        return $contremarque->getCurrentCommercialId();
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getClonedQuote(): ?Quote
    {
        return $this->clonedQuote;
    }

    public function setClonedQuote(?Quote $clonedQuote): void
    {
        $this->clonedQuote = $clonedQuote;
    }


    public function getOriginalQuote(): ?Quote
    {
        return $this->originalQuote;
    }

    public function setOriginalQuote(?Quote $originalQuote): void
    {
        $this->originalQuote = $originalQuote;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): void
    {
        $this->reference = $reference;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getCarpetOrderDetail(): Collection
    {
        return $this->carpetOrderDetail;
    }

    public function setCarpetOrderDetail(Collection $carpetOrderDetail): void
    {
        $this->carpetOrderDetail = $carpetOrderDetail;
    }


    public function getContremarque(): ?Contremarque
    {
        return $this->contremarque;
    }

    public function setContremarque(?Contremarque $contremarque): void
    {
        $this->contremarque = $contremarque;
    }


    public function toArray(): array
    {
        $details = [];
        foreach ($this->getCarpetOrderDetail() as $detail) {
            $rnAttributions = [];
            foreach ($detail->getRnAttributions() as $attribution) {
                if ($attribution->getCanceledAt() === null && $attribution->getAttributedAt() !== null) {
                    $rnAttributions[] = $attribution->toArray();
                }
            
            }

            $details[] = [
                'id' => $detail->getId(),
                'quote_detail_id' => $detail->getQuoteDetailId()->getId(),
                'rnAttributions' => $rnAttributions,
            ];
        }
        return [
            'id' => $this->getId(),
            'OriginalQuoteId' => $this->getOriginalQuote()->getId(),
            'refQuote' => $this->getOriginalQuote()->getReference(),
            'reference' => $this->getReference(),
            'contremarque' => $this->getContremarqueId()?->getId(),
            'contremarqueData' => $this->getContremarqueId()?->toArray(),
            'commercialId' => $this->getCurrentCommercialId(),
            'created_at' => $this->getCreatedAt()?->format('Y-m-d H:i:s'),
            'updated_at' => $this->getUpdatedAt()?->format('Y-m-d H:i:s'),
            'carpetOrderDetail' => $details
        ];
    }

}
