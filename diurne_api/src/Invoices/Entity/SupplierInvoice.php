<?php

namespace App\Invoices\Entity;

use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Invoices\Entity\SupplierInvoicePrices;
use App\Invoices\Entity\SupplierInvoiceDetail;
use App\Setting\Entity\Currency;
use App\User\Entity\User;
use App\Setting\Entity\Manufacturer;

#[ORM\Entity]
class SupplierInvoice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, unique: true)]
    private ?string $invoice_number = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $invoice_date = null;

    #[ORM\ManyToOne(targetEntity: Manufacturer::class)]
    #[ORM\JoinColumn(name: 'manufacturer_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    private ?Manufacturer $manufacturer = null;

    #[ORM\Column(length: 50)]
    private ?string $packing_list = null;

    #[ORM\Column(length: 50)]
    private ?string $air_way = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6)]
    private ?string $fret_total = null;

    #[ORM\ManyToOne(targetEntity: Currency::class)]
    #[ORM\JoinColumn(name: 'currency_id', referencedColumnName: 'id', nullable: false)]
    private ?Currency $currency = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'author_id', nullable: false)]
    private ?User $author = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6)]
    private ?string $amount_other = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6)]
    private ?string $weight = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isincluded = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $weight_total = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $surface_total = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $invoice_total = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $theoretical_total = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $updatedAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $createdAt = null;

    #[ORM\OneToOne(mappedBy: 'supplierInvoice', cascade: ['persist', 'remove'])]
    private ?SupplierInvoicePrices $prices = null;

    #[ORM\OneToMany(mappedBy: 'supplierInvoice', targetEntity: SupplierInvoiceDetail::class, cascade: ['persist'])]
    private Collection $supplierInvoiceDetails;

    public function __construct()
    {
        $this->supplierInvoiceDetails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInvoiceNumber(): ?string
    {
        return $this->invoice_number;
    }

    public function setInvoiceNumber(string $invoice_number): self
    {
        $this->invoice_number = $invoice_number;
        return $this;
    }

    public function getInvoiceDate(): ?DateTimeInterface
    {
        return $this->invoice_date;
    }

    public function setInvoiceDate(DateTimeInterface $invoice_date): self
    {
        $this->invoice_date = $invoice_date;
        return $this;
    }

    public function getManufacturer(): ?Manufacturer
    {
        return $this->manufacturer;
    }

    public function setManufacturer(?Manufacturer $manufacturer): self
    {
        $this->manufacturer = $manufacturer;
        return $this;
    }

    public function getPackingList(): ?string
    {
        return $this->packing_list;
    }

    public function setPackingList(string $packing_list): self
    {
        $this->packing_list = $packing_list;
        return $this;
    }

    public function getAirWay(): ?string
    {
        return $this->air_way;
    }

    public function setAirWay(string $air_way): self
    {
        $this->air_way = $air_way;
        return $this;
    }

    public function getFretTotal(): ?string
    {
        return $this->fret_total;
    }

    public function setFretTotal(string $fret_total): self
    {
        $this->fret_total = $fret_total;
        return $this;
    }

    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }

    public function setCurrency(?Currency $currency): self
    {
        $this->currency = $currency;
        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;
        return $this;
    }

    public function getAmountOther(): ?string
    {
        return $this->amount_other;
    }

    public function setAmountOther(string $amount_other): self
    {
        $this->amount_other = $amount_other;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function isIsincluded(): ?bool
    {
        return $this->isincluded;
    }

    public function setIsincluded(?bool $isincluded): self
    {
        $this->isincluded = $isincluded;
        return $this;
    }

    public function getWeightTotal(): ?string
    {
        return $this->weight_total;
    }

    public function setWeightTotal(?string $weight_total): self
    {
        $this->weight_total = $weight_total;
        return $this;
    }

    public function getSurfaceTotal(): ?string
    {
        return $this->surface_total;
    }

    public function setSurfaceTotal(?string $surface_total): self
    {
        $this->surface_total = $surface_total;
        return $this;
    }

    public function getInvoiceTotal(): ?string
    {
        return $this->invoice_total;
    }

    public function setInvoiceTotal(?string $invoice_total): self
    {
        $this->invoice_total = $invoice_total;
        return $this;
    }

    public function getTheoreticalTotal(): ?string
    {
        return $this->theoretical_total;
    }

    public function setTheoreticalTotal(?string $theoretical_total): self
    {
        $this->theoretical_total = $theoretical_total;
        return $this;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getPrices(): ?SupplierInvoicePrices
    {
        return $this->prices;
    }

    public function setPrices(?SupplierInvoicePrices $prices): self
    {
        // set the owning side of the relation if necessary
        if ($prices && $prices->getSupplierInvoice() !== $this) {
            $prices->setSupplierInvoice($this);
        }
        $this->prices = $prices;

        return $this;
    }

    public function getSupplierInvoiceDetails(): Collection
    {
        return $this->supplierInvoiceDetails;
    }

    public function addSupplierInvoiceDetail(SupplierInvoiceDetail $detail): self
    {
        if (!$this->supplierInvoiceDetails->contains($detail)) {
            $this->supplierInvoiceDetails->add($detail);
            $detail->setSupplierInvoice($this);
        }

        return $this;
    }

    public function removeSupplierInvoiceDetail(SupplierInvoiceDetail $detail): self
    {
        if ($this->supplierInvoiceDetails->removeElement($detail)) {
            if ($detail->getSupplierInvoice() === $this) {
                $detail->setSupplierInvoice(null);
            }
        }

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'invoice_number' => $this->invoice_number,
            'invoice_date' => $this->invoice_date?->format('Y-m-d H:i:s'),
            'manufacturer' => $this->manufacturer ? ['id' => $this->manufacturer->getId(), 'name' => $this->manufacturer->getName(), 'company' => $this->manufacturer->getCompany()] : null,
            'packing_list' => $this->packing_list,
            'air_way' => $this->air_way,
            'fret_total' => $this->fret_total,
            'currency_id' => $this->currency?->getId(),
            'author_id' => $this->author?->getId(),
            'amount_other' => $this->amount_other,
            'weight' => $this->weight,
            'description' => $this->description,
            'isincluded' => $this->isincluded,
            'weight_total' => $this->weight_total,
            'surface_total' => $this->surface_total,
            'invoice_total' => $this->invoice_total,
            'theoretical_total' => $this->theoretical_total,
            'updatedAt' => $this->updatedAt?->format('Y-m-d H:i:s'),
            'createdAt' => $this->createdAt?->format('Y-m-d H:i:s'),
            'prices' => $this->prices?->toArray(),
            'supplierInvoiceDetails' => array_map(
                fn($detail) => $detail->toArray(),
                array_filter(
                    $this->supplierInvoiceDetails->toArray(),
                    fn($detail) => !$detail->isDeleted()
                )
            ),
        ];
    }
}
