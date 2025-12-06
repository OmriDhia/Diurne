<?php

namespace App\Contremarque\Entity;

use App\Common\Calculator\Utils\Tools;
use DateTimeImmutable;
use App\Contremarque\Entity\OA\Property;
use InvalidArgumentException;

use App\Contremarque\Entity\OrderPayment\OrderPaymentDetail;
use App\Setting\Entity\CollectionGroup;
use App\Setting\Entity\Currency;
use App\Setting\Entity\Tarif;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class QuoteDetail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'quoteDetails')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Quote $quote = null;

    #[ORM\ManyToOne(targetEntity: Location::class, inversedBy: 'quoteDetails')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Location $location = null;

    #[ORM\ManyToOne(targetEntity: CarpetSpecification::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(onDelete: "CASCADE")]
    private ?CarpetSpecification $carpetSpecification = null;

    #[ORM\Column(nullable: true)]
    private ?bool $applyLargeProjectRate = null;

    #[ORM\Column(nullable: true)]
    private ?bool $applyProposedDiscount = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $proposedDiscountRate = null;

    #[ORM\Column(nullable: true)]
    private ?bool $calculateFromTotalExcludingTax = null;

    #[ORM\ManyToOne]
    private ?Currency $currency = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comment = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $totalPriceRate = null;

    #[ORM\Column]
    private ?bool $isValidated = false;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $validatedAt = null;

    #[ORM\Column(nullable: true)]
    private ?int $wantedQuantity = null;

    #[ORM\Column]
    private ?int $estimatedDeliveryTime = null;

    #[ORM\Column(nullable: true)]
    private ?bool $inStockCarpet = null;

    /**
     * @var Collection<int, CarpetPriceBase>
     */
    #[ORM\OneToMany(targetEntity: CarpetPriceBase::class, mappedBy: 'quoteDetail', cascade: ['persist', 'remove'])]
    private Collection $carpetPriceBases;

    /**
     * @var Collection<int, CarpetSpecificTreatment>
     */
    #[ORM\OneToMany(targetEntity: CarpetSpecificTreatment::class, mappedBy: 'quoteDetail')]
    #[ORM\JoinColumn(onDelete: "CASCADE")]
    private Collection $carpetSpecificTreatments;

    #[ORM\ManyToOne]
    private ?Tarif $tarif = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $impactOnTheQuotePrice = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $reference = null;

    #[Property(
        type: 'number',
        format: 'float',
        description: 'The calculated surface in square meters'
    )]
    private ?float $surface = null;

    #[ORM\Column]
    private ?bool $active = null;

    #[ORM\Column(name: 'area_square_meter', type: 'float', nullable: true)]
    private ?float $areaSquareMeter = null;

    #[ORM\Column(name: 'area_square_feet', type: 'float', nullable: true)]
    private ?float $areaSquareFeet = null;

    #[ORM\Column(name: 'rn', type: 'string', length: 50, nullable: true)]
    private ?string $rn = null;

    #[ORM\ManyToOne]
    private ?CollectionGroup $collectionGroupUsedInCalcul = null;

    #[ORM\ManyToOne]
    private ?CarpetDesignOrder $carpetDesignOrder = null;

    #[ORM\OneToMany(targetEntity: OrderPaymentDetail::class, mappedBy: "quoteDetail")]
    #[ORM\JoinColumn(onDelete: "CASCADE")]
    private Collection $orderPaymentDetails;

    public function __construct()
    {
        $this->carpetPriceBases = new ArrayCollection();
        $this->carpetSpecificTreatments = new ArrayCollection();
        $this->active = true;
        $this->orderPaymentDetails = new ArrayCollection();
    }

    public function getOrderPaymentDetails(): Collection
    {
        return $this->orderPaymentDetails;
    }

    public function addOrderPaymentDetail(OrderPaymentDetail $orderPaymentDetail): self
    {
        if (!$this->orderPaymentDetails->contains($orderPaymentDetail)) {
            $this->orderPaymentDetails->add($orderPaymentDetail);
            $orderPaymentDetail->setQuoteDetail($this);
        }
        return $this;
    }

    public function removeOrderPaymentDetail(OrderPaymentDetail $orderPaymentDetail): self
    {
        if ($this->orderPaymentDetails->removeElement($orderPaymentDetail)) {
            if ($orderPaymentDetail->getQuoteDetail() === $this) {
                $orderPaymentDetail->setQuoteDetail(null);
            }
        }
        return $this;
    }

    public function setApplyLargeProjectRate(?bool $applyLargeProjectRate): static
    {
        $this->applyLargeProjectRate = $applyLargeProjectRate;
        return $this;
    }

    public function setApplyProposedDiscount(?bool $applyProposedDiscount): static
    {
        $this->applyProposedDiscount = $applyProposedDiscount;
        return $this;
    }

    public function setCalculateFromTotalExcludingTax(?bool $calculateFromTotalExcludingTax): static
    {
        $this->calculateFromTotalExcludingTax = $calculateFromTotalExcludingTax;
        return $this;
    }

    public function setValidated(bool $isValidated): static
    {
        $this->isValidated = $isValidated;
        return $this;
    }

    public function setInStockCarpet(?bool $inStockCarpet): static
    {
        $this->inStockCarpet = $inStockCarpet;
        return $this;
    }

    /**
     * @return Collection<int, CarpetPriceBase>
     */
    public function getCarpetPriceBases(): Collection
    {
        return $this->carpetPriceBases;
    }

    public function addCarpetPriceBase(CarpetPriceBase $carpetPriceBase): static
    {
        if (!$this->carpetPriceBases->contains($carpetPriceBase)) {
            $this->carpetPriceBases->add($carpetPriceBase);
            $carpetPriceBase->setQuoteDetail($this);
        }
        return $this;
    }

    public function removeCarpetPriceBase(CarpetPriceBase $carpetPriceBase): static
    {
        if ($this->carpetPriceBases->removeElement($carpetPriceBase)) {
            if ($carpetPriceBase->getQuoteDetail() === $this) {
                $carpetPriceBase->setQuoteDetail(null);
            }
        }
        return $this;
    }

    public function addCarpetSpecificTreatment(CarpetSpecificTreatment $carpetSpecificTreatment): static
    {
        if (!$this->carpetSpecificTreatments->contains($carpetSpecificTreatment)) {
            $this->carpetSpecificTreatments->add($carpetSpecificTreatment);
            $carpetSpecificTreatment->setQuoteDetail($this);
        }
        return $this;
    }

    public function removeCarpetSpecificTreatment(CarpetSpecificTreatment $carpetSpecificTreatment): static
    {
        if ($this->carpetSpecificTreatments->removeElement($carpetSpecificTreatment)) {
            if ($carpetSpecificTreatment->getQuoteDetail() === $this) {
                $carpetSpecificTreatment->setQuoteDetail(null);
            }
        }
        return $this;
    }

    public function toArray(): array
    {
        $priceTypes = [
            ['name' => 'Tarif'],
            ['name' => 'Tarif grand projet'],
            ['name' => 'Remise proposee'],
            ['name' => 'Prix propose avant remise complementaire'],
            ['name' => 'Prix propose'],
        ];

        $result = [];

        if (!empty($priceTypes)) {
            foreach ($priceTypes as $priceType) {
                $typeName = Tools::slugify($priceType['name']);
                $prices = [];
                foreach ($this->carpetPriceBases as $priceBase) {
                    if ($priceBase->getPriceType()->getName() === $priceType['name']) {
                        $result[$typeName]['totalPriceHt'] = $priceBase->getTotalPriceHt();
                        $result[$typeName]['totalPriceTtc'] = $priceBase->getTotalPriceTtc();
                        $priceSimulators = $priceBase->getPriceSimulators();
                        if ($priceSimulators->count()) {
                            foreach ($priceSimulators as $simulator) {
                                $prices[$simulator->getUnit()] = [
                                    'unit' => $simulator->getUnit(),
                                    'price' => $simulator->getUnitPriceHt(),
                                ];
                            }
                        }
                    }
                }
                if (!empty($prices)) {
                    $result[$typeName] = array_merge($result[$typeName] ?? [], $prices);
                }
            }
        }

        return [
            'id' => $this->getId(),
            'reference' => $this->getReference(),
            'quote' => $this->getQuote() ? $this->getQuote()->getId() : null,
            'location' => $this->getLocation() ? $this->getLocation()->toArray() : null,
            'carpetSpecification' => $this->getCarpetSpecification() ? $this->getCarpetSpecification()->toArray() : null,
            'applyLargeProjectRate' => $this->isApplyLargeProjectRate(),
            'applyProposedDiscount' => $this->isApplyProposedDiscount(),
            'proposedDiscountRate' => $this->getProposedDiscountRate(),
            'calculateFromTotalExcludingTax' => $this->isCalculateFromTotalExcludingTax(),
            'currency' => $this->getCurrency() ? $this->getCurrency()->toArray() : null,
            'comment' => $this->getComment(),
            'totalPriceRate' => $this->getTotalPriceRate(),
            'isValidated' => $this->isValidated(),
            'validatedAt' => $this->getValidatedAt() ? $this->getValidatedAt()->format('Y-m-d H:i:s') : null,
            'wantedQuantity' => $this->getWantedQuantity(),
            'estimatedDeliveryTime' => $this->getEstimatedDeliveryTime(),
            'inStockCarpet' => $this->isInStockCarpet(),
            'tarif' => $this->getTarif() ? $this->getTarif()->toArray() : null,
            'impactOnTheQuotePrice' => $this->getImpactOnTheQuotePrice(),
            'prices' => $result,
            'carpetSpecificTreatments' => $this->getCarpetSpecificTreatments()
                ->map(fn($treatment) => $treatment->toArray())
                ->toArray(),
            'orderPaymentDetails' => $this->getOrderPaymentDetails()
                ->map(fn($orderPaymentDetail) => $orderPaymentDetail->toarray())
                ->toArray(),
            'active' => $this->isActive(),
            'areaSquareMeter' => $this->getAreaSquareMeter(),
            'areaSquareFeet' => $this->getAreaSquareFeet(),
            'rn' => $this->getRn(),
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): static
    {
        $this->reference = $reference;
        return $this;
    }

    public function getQuote(): ?Quote
    {
        return $this->quote;
    }

    public function setQuote(?Quote $quote): static
    {
        $this->quote = $quote;
        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): static
    {
        $this->location = $location;
        return $this;
    }

    public function getCarpetSpecification(): ?CarpetSpecification
    {
        return $this->carpetSpecification;
    }

    public function setCarpetSpecification(?CarpetSpecification $carpetSpecification): static
    {
        $this->carpetSpecification = $carpetSpecification;
        return $this;
    }

    public function isApplyLargeProjectRate(): ?bool
    {
        return $this->applyLargeProjectRate;
    }

    public function isApplyProposedDiscount(): ?bool
    {
        return $this->applyProposedDiscount;
    }

    public function getProposedDiscountRate(): ?string
    {
        return $this->proposedDiscountRate;
    }

    public function setProposedDiscountRate(?string $proposedDiscountRate): static
    {
        $this->proposedDiscountRate = $proposedDiscountRate;
        return $this;
    }

    public function isCalculateFromTotalExcludingTax(): ?bool
    {
        return $this->calculateFromTotalExcludingTax;
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

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): static
    {
        $this->comment = $comment;
        return $this;
    }

    public function getTotalPriceRate(): ?string
    {
        return $this->totalPriceRate;
    }

    public function setTotalPriceRate(?string $totalPriceRate): static
    {
        $this->totalPriceRate = $totalPriceRate;
        return $this;
    }

    public function isValidated(): ?bool
    {
        return $this->isValidated;
    }

    public function getValidatedAt(): ?DateTimeImmutable
    {
        return $this->validatedAt;
    }

    public function setValidatedAt(?DateTimeImmutable $validatedAt): static
    {
        $this->validatedAt = $validatedAt;
        return $this;
    }

    public function getWantedQuantity(): ?int
    {
        return $this->wantedQuantity;
    }

    public function setWantedQuantity(?int $wantedQuantity): static
    {
        $this->wantedQuantity = $wantedQuantity;
        return $this;
    }

    public function getEstimatedDeliveryTime(): ?int
    {
        return $this->estimatedDeliveryTime;
    }

    public function setEstimatedDeliveryTime(int $estimatedDeliveryTime): static
    {
        $this->estimatedDeliveryTime = $estimatedDeliveryTime;
        return $this;
    }

    public function isInStockCarpet(): ?bool
    {
        return $this->inStockCarpet;
    }

    public function getTarif(): ?Tarif
    {
        return $this->tarif;
    }

    public function setTarif(?Tarif $tarif): static
    {
        $this->tarif = $tarif;
        return $this;
    }

    public function getImpactOnTheQuotePrice(): ?string
    {
        return $this->impactOnTheQuotePrice;
    }

    public function setImpactOnTheQuotePrice(?string $impactOnTheQuotePrice): static
    {
        $this->impactOnTheQuotePrice = $impactOnTheQuotePrice;
        return $this;
    }

    public function getCarpetSpecificTreatments(): Collection
    {
        return $this->carpetSpecificTreatments;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function getAreaSquareMeter(): ?float
    {
        return $this->areaSquareMeter;
    }

    public function setAreaSquareMeter(?float $areaSquareMeter): self
    {
        $this->areaSquareMeter = $areaSquareMeter;
        return $this;
    }

    public function getAreaSquareFeet(): ?float
    {
        return $this->areaSquareFeet;
    }

    public function setAreaSquareFeet(?float $areaSquareFeet): self
    {
        $this->areaSquareFeet = $areaSquareFeet;
        return $this;
    }

    public function getRn(): ?string
    {
        return $this->rn;
    }

    public function setRn(?string $rn): self
    {
        $this->rn = $rn;
        return $this;
    }

    public function getSurface(): float|null
    {
        if (!empty($this->getAreaSquareMeter())) {
            return $this->getAreaSquareMeter();
        }

        $dimensions = [];
        $carpetSpecification = $this->getCarpetSpecification();
        if (!empty($carpetSpecification)) {
            if ($carpetSpecification->getCarpetDimensions()->count()) {
                foreach ($carpetSpecification->getCarpetDimensions() as $carpetDimension) {
                    $measurement = $carpetDimension->getMesurement();
                    if (empty($measurement)) {
                        continue;
                    }
                    $dimensions[$measurement->getName()] = [];
                    $dimensionValues = $carpetDimension->getDimensionValues();

                    if ($dimensionValues->count()) {
                        foreach ($dimensionValues as $index => $dimensionValue) {
                            if (null !== $dimensionValue) {
                                $dimensions[$measurement->getName()][$index] = [
                                    'unit_id' => !empty($dimensionValue->getUnit()) ? $dimensionValue->getUnit()->getId() : 0,
                                    'unit_name' => !empty($dimensionValue->getUnit()) ? $dimensionValue->getUnit()->getName() : null,
                                    'unit_abbreviation' => !empty($dimensionValue->getUnit()) ? $dimensionValue->getUnit()->getAbbreviation() : null,
                                    'value' => $dimensionValue->getValue(),
                                ];
                            }
                        }
                    }
                }
            }
        }

        return $this->getSurfaceInSquareMeters($dimensions);
    }

    public function getSurfaceInSquareMeters(array $dimensions): float
    {
        if (empty($dimensions)) {
            return 0;
        }
        $unitConversion = [
            'cm' => 0.01, // centimeters to meters
            'ft' => 0.3048, // feet to meters
            'inch' => 0.0254, // inches to meters
        ];

        $largeur = null;
        foreach ($dimensions['Largeur'] as $item) {
            if (isset($unitConversion[$item['unit_abbreviation']])) {
                $largeur = $item['value'] * $unitConversion[$item['unit_abbreviation']];
                break;
            }
        }

        $longueur = null;
        if (!empty($dimensions['Longueur'])) {
            foreach ($dimensions['Longueur'] as $item) {
                if (isset($unitConversion[$item['unit_abbreviation']])) {
                    $longueur = $item['value'] * $unitConversion[$item['unit_abbreviation']];
                    break;
                }
            }
        }

        return $largeur * $longueur * (int)$this->getWantedQuantity();
    }

    public function extractDimensionsInCm(): array
    {
        $dimensions = [];
        $carpetSpecification = $this->getCarpetSpecification();

        if (empty($carpetSpecification)) {
            throw new InvalidArgumentException('Carpet specification is empty');
        }


        foreach ($carpetSpecification->getCarpetDimensions() as $carpetDimension) {
            $measurement = $carpetDimension->getMesurement();
            if (!$measurement) {
                continue;
            }
            if (empty($dimensions[$measurement->getName()])) {
                $dimensions[$measurement->getName()] = [];
            }

            foreach ($carpetDimension->getDimensionValues() as $index => $dimensionValue) {
                if (!empty($dimensionValue->getValue()) && $dimensionValue->getValue() != (float)0 && empty($dimensions[$measurement->getName()][$index])) {
                    $dimensions[$measurement->getName()][$index] = [
                        'unit_id' => !empty($dimensionValue->getUnit()) ? $dimensionValue->getUnit()->getId() : 0,
                        'unit_name' => !empty($dimensionValue->getUnit()) ? $dimensionValue->getUnit()->getName() : null,
                        'unit_abbreviation' => !empty($dimensionValue->getUnit()) ? $dimensionValue->getUnit()->getAbbreviation() : null,
                        'value' => $dimensionValue->getValue(),
                    ];
                }
            }
        }

        $largeurCm = $this->calculateDimensionInCm($dimensions, 'Largeur');
        $longueurCm = $this->calculateDimensionInCm($dimensions, 'Longueur');

        return [
            'Largeur' => $largeurCm,
            'Longueur' => $longueurCm,
        ];
    }

    private function calculateDimensionInCm(array $dimensions, string $dimensionName): ?float
    {
        if (empty($dimensions[$dimensionName])) {
            return null;
        }
        foreach ($dimensions[$dimensionName] as $dimension) {
            if ($dimension['unit_abbreviation'] === 'cm') {
                return $dimension['value'];
            }
        }

        return null;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;
        return $this;
    }

    public function getCollectionGroupUsedInCalcul(): ?CollectionGroup
    {
        return $this->collectionGroupUsedInCalcul;
    }

    public function setCollectionGroupUsedInCalcul(?CollectionGroup $collectionGroupUsedInCalcul): static
    {
        $this->collectionGroupUsedInCalcul = $collectionGroupUsedInCalcul;
        return $this;
    }

    public function getCarpetDesignOrder(): ?CarpetDesignOrder
    {
        return $this->carpetDesignOrder;
    }

    public function setCarpetDesignOrder(?CarpetDesignOrder $carpetDesignOrder): static
    {
        $this->carpetDesignOrder = $carpetDesignOrder;
        return $this;
    }

    public function __clone()
    {
        $this->id = null;
        $this->reference = null; // This will be set by the cloner
        $this->carpetPriceBases = new ArrayCollection();
        $this->carpetSpecificTreatments = new ArrayCollection();
        $this->orderPaymentDetails = new ArrayCollection();
    }
}
