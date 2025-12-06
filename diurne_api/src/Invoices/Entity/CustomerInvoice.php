<?php

namespace App\Invoices\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Setting\Entity\Carrier;
use App\Contact\Entity\Customer;
use App\Contremarque\Entity\CarpetOrder\CarpetOrder;
use App\Contact\Entity\Customer as Client;
use App\Contremarque\Entity\InvoiceType as InvoiceTypeEntity;
use App\Contremarque\Entity\Regulation;
use App\Contremarque\Entity\TarifExpedition;
use App\Contremarque\Entity\Mesurement;
use App\Setting\Entity\Currency;
use App\Setting\Entity\Conversion;
use App\Setting\Entity\Language;
use App\Workshop\Entity\Carpet;
use App\Invoices\Entity\CustomerInvoiceDetail;
use App\Setting\Entity\TaxRule;

#[ORM\Entity]
class CustomerInvoice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, unique: true)]
    private ?string $invoice_number = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $invoice_date = null;

    #[ORM\Column]
    private ?int $invoice_type = null;

    #[ORM\ManyToOne(targetEntity: Carrier::class)]
    private ?Carrier $carrier = null;

    #[ORM\ManyToOne(targetEntity: Customer::class)]
    private ?Customer $customer = null;

    #[ORM\ManyToOne(targetEntity: CarpetOrder::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
    private ?CarpetOrder $carpetOrder = null;

    #[ORM\ManyToOne(targetEntity: Client::class)]
    private ?Client $prescriber = null;

    #[ORM\ManyToOne(targetEntity: InvoiceTypeEntity::class)]
    private ?InvoiceTypeEntity $invoiceTypeEntity = null;

    #[ORM\ManyToOne(targetEntity: Currency::class)]
    private ?Currency $currency = null;

    #[ORM\ManyToOne(targetEntity: Conversion::class)]
    private ?Conversion $conversion = null;

    #[ORM\ManyToOne(targetEntity: Language::class)]
    private ?Language $language = null;

    #[ORM\ManyToOne(targetEntity: Mesurement::class)]
    private ?Mesurement $lmesurement = null;

    #[ORM\ManyToOne(targetEntity: Regulation::class)]
    private ?Regulation $regulation = null;

    #[ORM\ManyToOne(targetEntity: TarifExpedition::class)]
    private ?TarifExpedition $tarifExpedition = null;

    #[ORM\ManyToOne(targetEntity: Carpet::class)]
    private ?Carpet $rn = null;

    #[ORM\ManyToOne(targetEntity: TaxRule::class)]
    private ?TaxRule $taxRule = null;

    #[ORM\Column(nullable: true)]
    private ?int $commercialId = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $number = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $project = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $updatedAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?int $quantity_total = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6)]
    private ?string $shipping_costs_ht = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6)]
    private ?string $billed = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $payment = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $total_ht = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $amount_ht = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $amount_tva = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $amount_ttc = null;

    #[ORM\OneToMany(targetEntity: CustomerInvoiceDetail::class, mappedBy: 'customerInvoice', cascade: ['persist'])]
    private Collection $customerInvoiceDetails;

    public function __construct()
    {
        $this->customerInvoiceDetails = new ArrayCollection();
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

    public function getInvoiceType(): ?int
    {
        return $this->invoice_type;
    }

    public function setInvoiceType(int $invoice_type): self
    {
        $this->invoice_type = $invoice_type;
        return $this;
    }

    public function getCarrier(): ?Carrier
    {
        return $this->carrier;
    }

    public function setCarrier(?Carrier $carrier): self
    {
        $this->carrier = $carrier;
        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;
        return $this;
    }

    public function getCarpetOrder(): ?CarpetOrder
    {
        return $this->carpetOrder;
    }

    public function setCarpetOrder(?CarpetOrder $carpetOrder): self
    {
        $this->carpetOrder = $carpetOrder;
        return $this;
    }

    public function getPrescriber(): ?Client
    {
        return $this->prescriber;
    }

    public function setPrescriber(?Client $prescriber): self
    {
        $this->prescriber = $prescriber;
        return $this;
    }

    public function getInvoiceTypeEntity(): ?InvoiceTypeEntity
    {
        return $this->invoiceTypeEntity;
    }

    public function setInvoiceTypeEntity(?InvoiceTypeEntity $invoiceTypeEntity): self
    {
        $this->invoiceTypeEntity = $invoiceTypeEntity;
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

    public function getConversion(): ?Conversion
    {
        return $this->conversion;
    }

    public function setConversion(?Conversion $conversion): self
    {
        $this->conversion = $conversion;
        return $this;
    }

    public function getLanguage(): ?Language
    {
        return $this->language;
    }

    public function setLanguage(?Language $language): self
    {
        $this->language = $language;
        return $this;
    }

    public function getLmesurement(): ?Mesurement
    {
        return $this->lmesurement;
    }

    public function setLmesurement(?Mesurement $lmesurement): self
    {
        $this->lmesurement = $lmesurement;
        return $this;
    }

    public function getRegulation(): ?Regulation
    {
        return $this->regulation;
    }

    public function setRegulation(?Regulation $regulation): self
    {
        $this->regulation = $regulation;
        return $this;
    }

    public function getTarifExpedition(): ?TarifExpedition
    {
        return $this->tarifExpedition;
    }

    public function setTarifExpedition(?TarifExpedition $tarifExpedition): self
    {
        $this->tarifExpedition = $tarifExpedition;
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

    public function getTaxRule(): ?TaxRule
    {
        return $this->taxRule;
    }

    public function setTaxRule(?TaxRule $taxRule): self
    {
        $this->taxRule = $taxRule;
        return $this;
    }

    public function getCommercialId(): ?int
    {
        return $this->commercialId;
    }

    public function setCommercialId(?int $commercialId): self
    {
        $this->commercialId = $commercialId;

        return $this;
    }

    public function getCurrentCommercialId(): ?int
    {
        return $this->carpetOrder?->getCurrentCommercialId();
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

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(?string $number): self
    {
        $this->number = $number;
        return $this;
    }

    public function getProject(): ?string
    {
        return $this->project;
    }

    public function setProject(?string $project): self
    {
        $this->project = $project;
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

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getQuantityTotal(): ?int
    {
        return $this->quantity_total;
    }

    public function setQuantityTotal(?int $quantity_total): self
    {
        $this->quantity_total = $quantity_total;
        return $this;
    }

    public function getShippingCostsHt(): ?string
    {
        return $this->shipping_costs_ht;
    }

    public function setShippingCostsHt(string $shipping_costs_ht): self
    {
        $this->shipping_costs_ht = $shipping_costs_ht;
        return $this;
    }

    public function getBilled(): ?string
    {
        return $this->billed;
    }

    public function setBilled(string $billed): self
    {
        $this->billed = $billed;
        return $this;
    }

    public function getPayment(): ?string
    {
        return $this->payment;
    }

    public function setPayment(?string $payment): self
    {
        $this->payment = $payment;
        return $this;
    }

    public function getTotalHt(): ?string
    {
        return $this->total_ht;
    }

    public function setTotalHt(?string $total_ht): self
    {
        $this->total_ht = $total_ht;
        return $this;
    }

    public function getAmountHt(): ?string
    {
        return $this->amount_ht;
    }

    public function setAmountHt(?string $amount_ht): self
    {
        $this->amount_ht = $amount_ht;
        return $this;
    }

    public function getAmountTva(): ?string
    {
        return $this->amount_tva;
    }

    public function setAmountTva(?string $amount_tva): self
    {
        $this->amount_tva = $amount_tva;
        return $this;
    }

    public function getAmountTtc(): ?string
    {
        return $this->amount_ttc;
    }

    public function setAmountTtc(?string $amount_ttc): self
    {
        $this->amount_ttc = $amount_ttc;
        return $this;
    }

    public function getCustomerInvoiceDetails(): Collection
    {
        return $this->customerInvoiceDetails;
    }

    public function addCustomerInvoiceDetail(CustomerInvoiceDetail $detail): self
    {
        if (!$this->customerInvoiceDetails->contains($detail)) {
            $this->customerInvoiceDetails->add($detail);
            $detail->setCustomerInvoice($this);
        }

        return $this;
    }

    public function removeCustomerInvoiceDetail(CustomerInvoiceDetail $detail): self
    {
        if ($this->customerInvoiceDetails->removeElement($detail)) {
            if ($detail->getCustomerInvoice() === $this) {
                $detail->setCustomerInvoice(null);
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
            'invoice_type' => $this->invoice_type,
            'carrier_id' => $this->carrier?->getId(),
            'customer_id' => $this->customer?->getId(),
            'carpet_order_id' => $this->carpetOrder?->toArray(),
            'prescriber_id' => $this->prescriber?->getId(),
            'invoice_type_entity' => $this->invoiceTypeEntity?->toArray(),
            'currency_id' => $this->currency?->getId(),
            'conversion_id' => $this->conversion?->getId(),
            'language_id' => $this->language?->getId(),
            'lmesurement_id' => $this->lmesurement?->getId(),
            'regulation_id' => $this->regulation?->getId(),
            'tarif_expedition_id' => $this->tarifExpedition?->getId(),
            'rn_id' => $this->rn?->getId(),
            'commercialId' => $this->getCurrentCommercialId(),
            'tax_rule_id' => $this->taxRule?->getId(),
            'description' => $this->description,
            'number' => $this->number,
            'project' => $this->project,
            'updatedAt' => $this->updatedAt?->format('Y-m-d H:i:s'),
            'createdAt' => $this->createdAt?->format('Y-m-d H:i:s'),
            'quantity_total' => $this->quantity_total,
            'shipping_costs_ht' => $this->shipping_costs_ht,
            'billed' => $this->billed,
            'payment' => $this->payment,
            'total_ht' => $this->total_ht,
            'amount_ht' => $this->amount_ht,
            'amount_tva' => $this->amount_tva,
            'amount_ttc' => $this->amount_ttc,
            'customerInvoiceDetails' => array_map(
                fn($detail) => $detail->toArray(),
                array_filter(
                    $this->customerInvoiceDetails->toArray(),
                    fn($detail) => !$detail->isDeleted()
                )
            ),
        ];
    }
}

