<?php

namespace App\Workshop\Entity;

use App\Setting\Entity\Quality;
use App\Setting\Entity\TarifGroup;
use App\Setting\Entity\Currency;
use App\Workshop\Entity\MaterialPurchasePrice;
use App\Workshop\Entity\WorkshopInformationMaterial;
use DateTimeInterface;
use App\Workshop\Repository\WorkshopInformationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class WorkshopInformation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?DateTimeInterface $launch_date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $expected_end_date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?DateTimeInterface $date_end_atelier_prev = null;


    #[ORM\Column]
    private ?int $production_time = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $order_silk_percentage = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6)]
    private ?string $ordered_width = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6)]
    private ?string $ordered_heigh = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6)]
    private ?string $ordered_surface = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6)]
    private ?string $real_width = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6)]
    private ?string $real_height = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6)]
    private ?string $real_surface = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6)]
    private ?string $reduction_rate = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $upcharge = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $comment_upcharge = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6)]
    private ?string $carpet_purchase_price_per_m2 = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $carpet_purchase_price_cmd = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6)]
    private ?string $carpet_purchase_price_theoretical = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6)]
    private ?string $carpet_purchase_price_invoice = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $penalty = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $shipping = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $tva = null;

    #[ORM\Column(nullable: true)]
    private ?bool $available_for_sale = null;

    #[ORM\Column(nullable: true)]
    private ?bool $sent = null;

    #[ORM\Column(nullable: true)]
    private ?bool $received_in_paris = null;

    #[ORM\Column(name: 'special_rate_in_paris', nullable: true)]
    private ?bool $special_rate = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $gross_margin = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $reference_on_invoice = null;

    #[ORM\Column(length: 50, nullable: true, unique: true)]
    private ?string $invoice_number = null;

    #[ORM\Column]
    private ?int $manufacturer_id = null;

    #[ORM\Column(nullable: true, options: ['default' => 1])]
    private ?int $copy = 1;

    #[ORM\ManyToOne(targetEntity: Currency::class)]
    #[ORM\JoinColumn(name: 'currency_id', referencedColumnName: 'id', nullable: true)]
    private ?Currency $currency = null;

    #[ORM\Column(length: 50)]
    private ?string $Rn = null;
    #[ORM\OneToOne(targetEntity: WorkshopOrder::class, mappedBy: 'workshopInformation')]
    private ?WorkshopOrder $workshopOrder = null;

    #[ORM\OneToMany(targetEntity: MaterialPurchasePrice::class, mappedBy: 'workshopInformation')]
    private Collection $materialPurchasePrices;
    #[ORM\OneToMany(targetEntity: WorkshopInformationMaterial::class, mappedBy: 'workshopInformation', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $workshopInformationMaterials;
    #[ORM\ManyToOne(targetEntity: Quality::class)]
    #[ORM\JoinColumn(name: "quality_id", referencedColumnName: "id", nullable: true)]
    private ?Quality $quality = null;

    #[ORM\ManyToOne(targetEntity: TarifGroup::class)]
    #[ORM\JoinColumn(name: "tarif_group_id", referencedColumnName: "id", nullable: true)]
    private ?TarifGroup $tarifGroup = null;

    public function __construct()
    {
        $this->materialPurchasePrices = new ArrayCollection();
        $this->workshopInformationMaterials = new ArrayCollection();
    }

    public function getMaterialPurchasePrices(): Collection
    {
        return $this->materialPurchasePrices;
    }

    public function addMaterialPurchasePrice(MaterialPurchasePrice $materialPurchasePrice): self
    {
        if (!$this->materialPurchasePrices->contains($materialPurchasePrice)) {
            $this->materialPurchasePrices[] = $materialPurchasePrice;
            $materialPurchasePrice->setWorkshopInformation($this);
        }
        return $this;
    }

    public function removeMaterialPurchasePrice(MaterialPurchasePrice $materialPurchasePrice): self
    {
        if ($this->materialPurchasePrices->removeElement($materialPurchasePrice)) {
            // set the owning side to null (unless already changed)
            if ($materialPurchasePrice->getWorkshopInformation() === $this) {
                $materialPurchasePrice->setWorkshopInformation(null);
            }
        }
        return $this;
    }

    public function getWorkshopOrder(): ?WorkshopOrder
    {
        return $this->workshopOrder;
    }

    public function setWorkshopOrder(WorkshopOrder $workshopOrder): self
    {
        if ($this->workshopOrder !== null && $this->workshopOrder->getWorkshopInformation() !== $this) {
            $this->workshopOrder->setWorkshopInformation(null);
        }

        $this->workshopOrder = $workshopOrder;
        if ($workshopOrder->getWorkshopInformation() !== $this) {
            $workshopOrder->setWorkshopInformation($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, WorkshopInformationMaterial>
     */
    public function getWorkshopInformationMaterials(): Collection
    {
        return $this->workshopInformationMaterials;
    }

    public function addWorkshopInformationMaterial(WorkshopInformationMaterial $workshopInformationMaterial): self
    {
        if (!$this->workshopInformationMaterials->contains($workshopInformationMaterial)) {
            $this->workshopInformationMaterials->add($workshopInformationMaterial);
            $workshopInformationMaterial->setWorkshopInformation($this);
        }

        return $this;
    }

    public function removeWorkshopInformationMaterial(WorkshopInformationMaterial $workshopInformationMaterial): self
    {
        if ($this->workshopInformationMaterials->removeElement($workshopInformationMaterial)) {
            if ($workshopInformationMaterial->getWorkshopInformation() === $this) {
                $workshopInformationMaterial->setWorkshopInformation(null);
            }
        }

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLaunchDate(): ?DateTimeInterface
    {
        return $this->launch_date;
    }

    public function setLaunchDate(?DateTimeInterface $launch_date): static
    {
        $this->launch_date = $launch_date;
        $this->updateProductionTimeFromDates();

        return $this;
    }

    public function getExpectedEndDate(): ?DateTimeInterface
    {
        return $this->expected_end_date;
    }

    public function setExpectedEndDate(?DateTimeInterface $expected_end_date): static
    {
        $this->expected_end_date = $expected_end_date;

        return $this;
    }

    public function getDateEndAtelierPrev(): ?DateTimeInterface
    {

        return $this->date_end_atelier_prev;
    }

    public function setDateEndAtelierPrev(?DateTimeInterface $date_end_atelier_prev): static
    {
        $this->date_end_atelier_prev = $date_end_atelier_prev;
        $this->updateProductionTimeFromDates();

        return $this;
    }

    public function getProductionTime(): ?int
    {
        return $this->production_time;
    }

    public function setProductionTime(?int $production_time): static
    {
        $this->production_time = $production_time;

        return $this;
    }

    private function updateProductionTimeFromDates(): void
    {
        if ($this->launch_date !== null && $this->date_end_atelier_prev !== null) {
            $interval = $this->launch_date->diff($this->date_end_atelier_prev);
            $this->production_time = (int)$interval->format('%a');
        } else {
            $this->production_time = null;
        }
    }

    public function getOrderSilkPercentage(): ?string
    {
        return $this->order_silk_percentage;
    }

    public function setOrderSilkPercentage(?string $order_silk_percentage): static
    {
        $this->order_silk_percentage = $order_silk_percentage;

        return $this;
    }

    public function getOrderedWidth(): ?string
    {
        return $this->ordered_width;
    }

    public function setOrderedWidth(string $ordered_width): static
    {
        $this->ordered_width = $ordered_width;

        return $this;
    }

    public function getOrderedHeigh(): ?string
    {
        return $this->ordered_heigh;
    }

    public function setOrderedHeigh(string $ordered_heigh): static
    {
        $this->ordered_heigh = $ordered_heigh;

        return $this;
    }

    public function getOrderedSurface(): ?string
    {
        return $this->ordered_surface;
    }

    public function setOrderedSurface(string $ordered_surface): static
    {
        $this->ordered_surface = $ordered_surface;

        return $this;
    }

    public function getRealWidth(): ?string
    {
        return $this->real_width;
    }

    public function setRealWidth(string $real_width): static
    {
        $this->real_width = $real_width;

        return $this;
    }

    public function getRealHeight(): ?string
    {
        return $this->real_height;
    }

    public function setRealHeight(string $real_height): static
    {
        $this->real_height = $real_height;

        return $this;
    }

    public function getRealSurface(): ?string
    {
        return $this->real_surface;
    }

    public function setRealSurface(string $real_surface): static
    {
        $this->real_surface = $real_surface;

        return $this;
    }

    public function getReductionRate(): ?string
    {
        return $this->reduction_rate;
    }

    public function setReductionRate(string $reduction_rate): static
    {
        $this->reduction_rate = $reduction_rate;

        return $this;
    }

    public function getUpcharge(): ?string
    {
        return $this->upcharge;
    }

    public function setUpcharge(?string $upcharge): static
    {
        $this->upcharge = $upcharge;

        return $this;
    }

    public function getCommentUpcharge(): ?string
    {
        return $this->comment_upcharge;
    }

    public function setCommentUpcharge(?string $comment_upcharge): static
    {
        $this->comment_upcharge = $comment_upcharge;

        return $this;
    }

    public function getCarpetPurchasePricePerM2(): ?string
    {
        return $this->carpet_purchase_price_per_m2;
    }

    public function setCarpetPurchasePricePerM2(string $carpet_purchase_price_per_m2): static
    {
        $this->carpet_purchase_price_per_m2 = $carpet_purchase_price_per_m2;

        return $this;
    }

    public function getCarpetPurchasePriceCmd(): ?string
    {
        return $this->carpet_purchase_price_cmd;
    }

    public function setCarpetPurchasePriceCmd(?string $carpet_purchase_price_cmd): static
    {
        $this->carpet_purchase_price_cmd = $carpet_purchase_price_cmd;

        return $this;
    }

    public function getCarpetPurchasePriceTheoretical(): ?string
    {
        return $this->carpet_purchase_price_theoretical;
    }

    public function setCarpetPurchasePriceTheoretical(string $carpet_purchase_price_theoretical): static
    {
        $this->carpet_purchase_price_theoretical = $carpet_purchase_price_theoretical;

        return $this;
    }

    public function getCarpetPurchasePriceInvoice(): ?string
    {
        return $this->carpet_purchase_price_invoice;
    }

    public function setCarpetPurchasePriceInvoice(string $carpet_purchase_price_invoice): static
    {
        $this->carpet_purchase_price_invoice = $carpet_purchase_price_invoice;

        return $this;
    }

    public function getPenalty(): ?string
    {
        return $this->penalty;
    }

    public function setPenalty(?string $penalty): static
    {
        $this->penalty = $penalty;

        return $this;
    }

    public function getShipping(): ?string
    {
        return $this->shipping;
    }

    public function setShipping(?string $shipping): static
    {
        $this->shipping = $shipping;

        return $this;
    }

    public function getTva(): ?string
    {
        return $this->tva;
    }

    public function setTva(?string $tva): static
    {
        $this->tva = $tva;

        return $this;
    }

    public function isAvailableForSale(): ?bool
    {
        return $this->available_for_sale;
    }

    public function setAvailableForSale(?bool $available_for_sale): static
    {
        $this->available_for_sale = $available_for_sale;

        return $this;
    }

    public function isSent(): ?bool
    {
        return $this->sent;
    }

    public function setSent(?bool $sent): static
    {
        $this->sent = $sent;

        return $this;
    }

    public function isReceivedInParis(): ?bool
    {
        return $this->received_in_paris;
    }

    public function setReceivedInParis(?bool $received_in_paris): static
    {
        $this->received_in_paris = $received_in_paris;

        return $this;
    }


    public function hasSpecialRate(): ?bool
    {
        return $this->special_rate;
    }

    public function setSpecialRate(?bool $special_rate): static
    {
        $this->special_rate = $special_rate;

        return $this;
    }

    public function getGrossMargin(): ?string
    {
        return $this->gross_margin;
    }

    public function setGrossMargin(?string $gross_margin): static
    {
        $this->gross_margin = $gross_margin;

        return $this;
    }

    public function getReferenceOnInvoice(): ?string
    {
        return $this->reference_on_invoice;
    }

    public function setReferenceOnInvoice(?string $reference_on_invoice): static
    {
        $this->reference_on_invoice = $reference_on_invoice;

        return $this;
    }

    public function getInvoiceNumber(): ?string
    {
        return $this->invoice_number;
    }

    public function setInvoiceNumber(?string $invoice_number): static
    {
        $this->invoice_number = $invoice_number;

        return $this;
    }

    public function getManufacturerId(): ?int
    {
        return $this->manufacturer_id;
    }

    public function setManufacturerId(int $manufacturer_id): static
    {
        $this->manufacturer_id = $manufacturer_id;

        return $this;
    }

    public function getCopy(): int
    {
        return $this->copy ?? 1;
    }

    public function setCopy(?int $copy): static
    {
        $this->copy = ($copy !== null && $copy > 0) ? $copy : 1;

        return $this;
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

    public function getIdCurrency(): ?int
    {
        return $this->currency ? $this->currency->getId() : null;
    }

    public function getRn(): ?string
    {
        return $this->Rn;
    }

    public function setRn(string $Rn): static
    {
        $this->Rn = $Rn;

        return $this;
    }

    public function getIdQuality(): ?int
    {
        return $this->quality ? $this->quality->getId() : null;
    }

    public function setQuality(?Quality $quality): self
    {
        $this->quality = $quality;
        return $this;
    }

    public function getQuality(): ?Quality
    {
        return $this->quality;
    }

    public function getIdTarifGroup(): ?int
    {
        return $this->tarifGroup ? $this->tarifGroup->getId() : null;
    }

    public function setTarifGroup(?TarifGroup $tarifGroup): self
    {
        $this->tarifGroup = $tarifGroup;
        return $this;
    }

    public function getTarifGroup(): ?TarifGroup
    {
        return $this->tarifGroup;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'launchDate' => $this->launch_date?->format('Y-m-d'),
            'expectedEndDate' => $this->expected_end_date?->format('Y-m-d H:i:s'),

            'dateEndAtelierPrev' => $this->date_end_atelier_prev?->format('Y-m-d'),

            'productionTime' => $this->getProductionTime(),
            'orderSilkPercentage' => $this->getOrderSilkPercentage(),
            'orderedWidth' => $this->getOrderedWidth(),
            'orderedHeigh' => $this->getOrderedHeigh(),
            'orderedSurface' => $this->getOrderedSurface(),
            'realWidth' => $this->getRealWidth(),
            'realHeight' => $this->getRealHeight(),
            'realSurface' => $this->getRealSurface(),
            'reductionRate' => $this->getReductionRate(),
            'upcharge' => $this->getUpcharge(),
            'commentUpcharge' => $this->getCommentUpcharge(),
            'carpetPurchasePricePerM2' => $this->getCarpetPurchasePricePerM2(),
            'carpetPurchasePriceCmd' => $this->getCarpetPurchasePriceCmd(),
            'carpetPurchasePriceTheoretical' => $this->getCarpetPurchasePriceTheoretical(),
            'carpetPurchasePriceInvoice' => $this->getCarpetPurchasePriceInvoice(),
            'penalty' => $this->getPenalty(),
            'shipping' => $this->getShipping(),
            'tva' => $this->getTva(),
            'availableForSale' => $this->isAvailableForSale(),
            'sent' => $this->isSent(),
            'receivedInParis' => $this->isReceivedInParis(),
            'specialRate' => $this->hasSpecialRate(),
            'grossMargin' => $this->getGrossMargin(),
            'referenceOnInvoice' => $this->getReferenceOnInvoice(),
            'invoiceNumber' => $this->getInvoiceNumber(),
            'manufacturerId' => $this->getManufacturerId(),
            'copy' => $this->getCopy(),
            'idCurrency' => $this->getIdCurrency(),
            'rn' => $this->getRn(),
            //'workshopOrder' => $this->workshopOrder?->toArray(),
            'idQuality' => $this->getIdQuality(),
            'idTarifGroup' => $this->getIdTarifGroup(),
            'quality' => $this->quality?->toArray(),
            'tarifGroup' => $this->tarifGroup?->toArray(),
            'currency' => $this->currency?->toArray(),
            'materialPurchasePrices' => array_map(
                static fn(MaterialPurchasePrice $mpp) => $mpp->toArray(),
                $this->materialPurchasePrices->toArray()
            ),
            'workshopInformationMaterials' => array_map(
                static fn(WorkshopInformationMaterial $material) => $material->toArray(),
                $this->workshopInformationMaterials->toArray()
            ),
        ];
    }
}
