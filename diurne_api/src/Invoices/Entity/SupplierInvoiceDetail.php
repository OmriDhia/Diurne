<?php

namespace App\Invoices\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Workshop\Entity\Carpet;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class SupplierInvoiceDetail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: SupplierInvoice::class, inversedBy: 'supplierInvoiceDetails')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?SupplierInvoice $supplierInvoice = null;

    #[ORM\ManyToOne(targetEntity: Carpet::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?Carpet $rn = null;

    #[ORM\Column(type: Types::STRING, length: 50, nullable: true)]
    private ?string $carpetNumber = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6)]
    private ?string $pricePerSquareMeter = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6)]
    private ?string $invoiceSurface = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6)]
    private ?string $invoiceAmount = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6)]
    private ?string $theoreticalPrice = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6)]
    private ?string $penalty = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6)]
    private ?string $producedSurface = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6)]
    private ?string $actualCreditAmount = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6)]
    private ?string $theoreticalCredit = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6)]
    private ?string $finalCarpetAmount = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6)]
    private ?string $weight = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6)]
    private ?string $weightPercentage = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6)]
    private ?string $freight = null;

    #[ORM\Column(type: "boolean", options: ["default" => false])]
    private bool $cleared = false;

    #[ORM\Column(type: "boolean", options: ["default" => false])]
    private bool $deleted = false;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?DateTimeInterface $deletedAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private DateTimeInterface $createdAt;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private DateTimeInterface $updatedAt;

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSupplierInvoice(): ?SupplierInvoice
    {
        return $this->supplierInvoice;
    }

    public function setSupplierInvoice(?SupplierInvoice $supplierInvoice): self
    {
        $this->supplierInvoice = $supplierInvoice;
        return $this;
    }

    public function getRn(): ?Carpet
    {
        return $this->rn;
    }

    public function setRn(?Carpet $rn): self
    {
        $this->rn = $rn;
        return $this;
    }

    public function getCarpetNumber(): ?string
    {
        return $this->carpetNumber;
    }

    public function setCarpetNumber(?string $carpetNumber): self
    {
        $this->carpetNumber = $carpetNumber;
        return $this;
    }

    public function getPricePerSquareMeter(): ?string
    {
        return $this->pricePerSquareMeter;
    }

    public function setPricePerSquareMeter(string $pricePerSquareMeter): self
    {
        $this->pricePerSquareMeter = $pricePerSquareMeter;
        return $this;
    }

    public function getInvoiceSurface(): ?string
    {
        return $this->invoiceSurface;
    }

    public function setInvoiceSurface(string $invoiceSurface): self
    {
        $this->invoiceSurface = $invoiceSurface;
        return $this;
    }

    public function getInvoiceAmount(): ?string
    {
        return $this->invoiceAmount;
    }

    public function setInvoiceAmount(string $invoiceAmount): self
    {
        $this->invoiceAmount = $invoiceAmount;
        return $this;
    }

    public function getTheoreticalPrice(): ?string
    {
        return $this->theoreticalPrice;
    }

    public function setTheoreticalPrice(string $theoreticalPrice): self
    {
        $this->theoreticalPrice = $theoreticalPrice;
        return $this;
    }

    public function getPenalty(): ?string
    {
        return $this->penalty;
    }

    public function setPenalty(string $penalty): self
    {
        $this->penalty = $penalty;
        return $this;
    }

    public function getProducedSurface(): ?string
    {
        return $this->producedSurface;
    }

    public function setProducedSurface(string $producedSurface): self
    {
        $this->producedSurface = $producedSurface;
        return $this;
    }

    public function getActualCreditAmount(): ?string
    {
        return $this->actualCreditAmount;
    }

    public function setActualCreditAmount(string $actualCreditAmount): self
    {
        $this->actualCreditAmount = $actualCreditAmount;
        return $this;
    }

    public function getTheoreticalCredit(): ?string
    {
        return $this->theoreticalCredit;
    }

    public function setTheoreticalCredit(string $theoreticalCredit): self
    {
        $this->theoreticalCredit = $theoreticalCredit;
        return $this;
    }

    public function getFinalCarpetAmount(): ?string
    {
        return $this->finalCarpetAmount;
    }

    public function setFinalCarpetAmount(string $finalCarpetAmount): self
    {
        $this->finalCarpetAmount = $finalCarpetAmount;
        return $this;
    }

    public function getWeight(): ?string
    {
        return $this->weight;
    }

    public function setWeight(string $weight): self
    {
        $this->weight = $weight;
        return $this;
    }

    public function getWeightPercentage(): ?string
    {
        return $this->weightPercentage;
    }

    public function setWeightPercentage(string $weightPercentage): self
    {
        $this->weightPercentage = $weightPercentage;
        return $this;
    }

    public function getFreight(): ?string
    {
        return $this->freight;
    }

    public function setFreight(string $freight): self
    {
        $this->freight = $freight;
        return $this;
    }

    public function isCleared(): bool
    {
        return $this->cleared;
    }

    public function setCleared(bool $cleared): self
    {
        $this->cleared = $cleared;
        return $this;
    }

    public function isDeleted(): bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): self
    {
        $this->deleted = $deleted;
        return $this;
    }

    public function getDeletedAt(): ?DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?DateTimeInterface $deletedAt): self
    {
        $this->deletedAt = $deletedAt;
        return $this;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'supplier_invoice_id' => $this->supplierInvoice?->getId(),
            'rn' => $this->rn?->getRnNumber(),
            'carpet_number' => $this->carpetNumber,
            'price_per_square_meter' => $this->pricePerSquareMeter,
            'invoice_surface' => $this->invoiceSurface,
            'invoice_amount' => $this->invoiceAmount,
            'theoretical_price' => $this->theoreticalPrice,
            'penalty' => $this->penalty,
            'produced_surface' => $this->producedSurface,
            'actual_credit_amount' => $this->actualCreditAmount,
            'theoretical_credit' => $this->theoreticalCredit,
            'final_carpet_amount' => $this->finalCarpetAmount,
            'weight' => $this->weight,
            'weight_percentage' => $this->weightPercentage,
            'freight' => $this->freight,
            'cleared' => $this->cleared,
            'deleted' => $this->deleted,
            'deletedAt' => $this->deletedAt,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
        ];
    }
}
