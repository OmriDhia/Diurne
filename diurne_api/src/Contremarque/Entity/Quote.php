<?php

namespace App\Contremarque\Entity;

use DateTimeImmutable;
use App\Contact\Entity\Address;
use App\Contremarque\Entity\OrderPayment\OrderPaymentDetail;
use App\Setting\Entity\Conversion;
use App\Setting\Entity\Currency;
use App\Setting\Entity\DiscountRule;
use App\Setting\Entity\Language;
use App\Setting\Entity\TaxRule;
use App\Setting\Entity\TransportCondition;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\Index(name: 'idx_quote_contremarque', columns: ['contremarque_id'])]
class Quote
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $reference = null;

    #[ORM\Column]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne]
    private ?TaxRule $taxRule = null;

    #[ORM\ManyToOne]
    private ?Currency $currency = null;

    #[ORM\ManyToOne]
    private ?Language $language = null;
    #[ORM\Column]
    private ?string $unitOfmeasurement = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $without_discount_price = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $additional_discount = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $total_discount_amount = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $total_discount_percentage = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $total_tax_excluded = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $shipping_price = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $tax = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $total_tax_included = null;

    #[ORM\Column(nullable: true)]
    private ?bool $quote_sent_to_customer = null;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $sentAt = null;

    #[ORM\Column(nullable: true)]
    private ?bool $transformed_into_an_order = null;

    #[ORM\Column(nullable: true)]
    private ?bool $archived = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $qualification_message = null;

    #[ORM\ManyToOne]
    private ?Conversion $conversion = null;

    /**
     * @var Collection<int, QuoteDetail>
     */
    #[ORM\OneToMany(targetEntity: QuoteDetail::class, mappedBy: 'quote', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $quoteDetails;

    #[ORM\ManyToOne]
    private ?DiscountRule $discountRule = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $otherTva = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $cumulatedDiscountAmount = null;

    #[ORM\ManyToOne]
    private ?Address $deliveryAddress = null;

    #[ORM\ManyToOne]
    private ?Address $invoiceAddress = null;
    #[ORM\Column]
    private ?bool $isValidated = false;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $validatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'quotes')]
    private ?TransportCondition $transportCondition = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $weight = null;

    #[ORM\ManyToOne(inversedBy: 'quotes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Contremarque $contremarque = null;

    #[ORM\OneToMany(targetEntity: OrderPaymentDetail::class, mappedBy: 'quote')]
    private Collection $orderPaymentDetails;
    #[ORM\Column(nullable: true)]
    private ?int $isCloneOf = null;

    #[ORM\Column(nullable: true)]
    private ?int $usedInOrder = null;

    #[ORM\Column(nullable: true)]
    private ?int $commercialId = null;

    public function getIsCloneOf(): ?int
    {
        return $this->isCloneOf;
    }

    public function setIsCloneOf(?int $isCloneOf): void
    {
        $this->isCloneOf = $isCloneOf;
    }

    public function getUsedInOrder(): ?int
    {
        return $this->usedInOrder;
    }

    public function setUsedInOrder(?int $usedInOrder): void
    {
        $this->usedInOrder = $usedInOrder;
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
        $contremarque = $this->getContremarque();
        if (!$contremarque) {
            return null;
        }

        return $contremarque->getCurrentCommercialId();
    }


    public function __construct()
    {
        $this->quoteDetails = new ArrayCollection();
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
            $orderPaymentDetail->setQuote($this);
        }

        return $this;
    }

    public function removeOrderPaymentDetail(OrderPaymentDetail $orderPaymentDetail): self
    {
        if ($this->orderPaymentDetails->removeElement($orderPaymentDetail)) {
            if ($orderPaymentDetail->getQuote() === $this) {
                $orderPaymentDetail->setQuote(null);
            }
        }

        return $this;
    }

    public function setQuoteSentToCustomer(bool $quote_sent_to_customer): static
    {
        $this->quote_sent_to_customer = $quote_sent_to_customer;

        return $this;
    }

    public function setTransformedIntoAnOrder(bool $transformed_into_an_order): static
    {
        $this->transformed_into_an_order = $transformed_into_an_order;

        return $this;
    }

    public function setArchived(?bool $archived): static
    {
        $this->archived = $archived;

        return $this;
    }

    public function addQuoteDetail(QuoteDetail $quoteDetail): static
    {
        if (!$this->quoteDetails->contains($quoteDetail)) {
            $this->quoteDetails->add($quoteDetail);
            $quoteDetail->setQuote($this);
        }

        return $this;
    }

    public function removeQuoteDetail(QuoteDetail $quoteDetail): static
    {
        if ($this->quoteDetails->removeElement($quoteDetail)) {
            // set the owning side to null (unless already changed)
            if ($quoteDetail->getQuote() === $this) {
                $quoteDetail->setQuote(null);
            }
        }

        return $this;
    }

    public function getDiscountRule(): ?DiscountRule
    {
        return $this->discountRule;
    }

    public function setDiscountRule(?DiscountRule $discountRule): static
    {
        $this->discountRule = $discountRule;

        return $this;
    }

    public function setIsValidated(?bool $isValidated): Quote
    {
        $this->isValidated = $isValidated;

        return $this;
    }

    /**
     * Helper function to convert QuoteDetails collection into an array.
     */
    private function getQuoteDetailsArray(): array
    {
        return $this->getQuoteDetails()->map(fn($detail) => $detail->toArray())->toArray();
    }

    public function __clone()
    {
        $this->id = null;
        $this->reference = null; // This will be set by the cloner
        $this->quoteDetails = new ArrayCollection();
        $this->orderPaymentDetails = new ArrayCollection();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'reference' => $this->getReference(),
            'createdAt' => $this->getCreatedAt()?->format('Y-m-d H:i:s'),
            'taxRule' => $this->getTaxRule()?->toArray(),
            'currency' => $this->getCurrency()?->toArray(),
            'language' => $this->getLanguage()?->toArray(),
            'unitOfMeasurement' => $this->getUnitOfmeasurement(),
            'withoutDiscountPrice' => $this->getWithoutDiscountPrice(),
            'additionalDiscount' => $this->getAdditionalDiscount(),
            'totalDiscountAmount' => $this->getTotalDiscountAmount(),
            'totalDiscountPercentage' => $this->getTotalDiscountPercentage(),
            'totalTaxExcluded' => $this->getTotalTaxExcluded(),
            'shippingPrice' => $this->getShippingPrice(),
            'tax' => $this->getTax(),
            'totalTaxIncluded' => $this->getTotalTaxIncluded(),
            'quoteSentToCustomer' => $this->isQuoteSentToCustomer(),
            'sentAt' => $this->getSentAt()?->format('Y-m-d H:i:s'),
            'transformedIntoAnOrder' => $this->isTransformedIntoAnOrder(),
            'archived' => $this->isArchived(),
            'qualificationMessage' => $this->getQualificationMessage(),
            'conversion' => $this->getConversion()?->toArray(),
            'deliveryAddress' => $this->getDeliveryAddress()?->toArray(),
            'invoiceAddress' => $this->getInvoiceAddress()?->toArray(),
            'isValidated' => $this->isValidated(),
            'validatedAt' => $this->getValidatedAt()?->format('Y-m-d H:i:s'),
            'transportCondition' => $this->getTransportCondition()?->toArray(),
            'quoteDetails' => $this->getQuoteDetailsArray(),
            'orderPaymentDetailsSum' => $this->getOrderPaymentDetailsSum(),
            'discountRule' => $this->getDiscountRule()?->toArray(),
            'otherTva' => $this->getOtherTva(),
            'cumulatedDiscountAmount' => $this->getCumulatedDiscountAmount(),
            'contremarqueId' => $this->getContremarque()?->getId(),
            'commercialId' => $this->getCurrentCommercialId(),
            'weight' => $this->getWeight(),
        ];
    }

    private function getOrderPaymentDetailsSum(): array
    {
        $sums = [
            'distribution' => '0',
            'allocatedAmountTtc' => '0',
            'remainingAmountTtc' => '0',
            'totalAmountTtc' => '0',
            'tva' => '0',
            'allocatedAmountHt' => '0',
        ];

        foreach ($this->getOrderPaymentDetails() as $orderPaymentDetail) {
            if ($orderPaymentDetail->getQuote() !== $this || $orderPaymentDetail->isDeleted()) {
                continue;
            }

            $sums['distribution'] = $this->addDecimalStrings($sums['distribution'], $orderPaymentDetail->getDistribution());
            $sums['allocatedAmountTtc'] = $this->addDecimalStrings($sums['allocatedAmountTtc'], $orderPaymentDetail->getAllocatedAmountTtc());
            $sums['remainingAmountTtc'] = $this->addDecimalStrings($sums['remainingAmountTtc'], $orderPaymentDetail->getRemainingAmountTtc());
            $sums['totalAmountTtc'] = $this->addDecimalStrings($sums['totalAmountTtc'], $orderPaymentDetail->getTotalAmountTtc());
            $sums['tva'] = $this->addDecimalStrings($sums['tva'], $orderPaymentDetail->getTva());
            $sums['allocatedAmountHt'] = $this->addDecimalStrings($sums['allocatedAmountHt'], $orderPaymentDetail->getAllocatedAmountHt());
        }

        return $sums;
    }

    private function addDecimalStrings(?string $left, ?string $right, int $scale = 6): string
    {
        $leftValue = $left ?? '0';
        $rightValue = $right ?? '0';

        if (function_exists('bcadd')) {
            return bcadd($leftValue, $rightValue, $scale);
        }

        $sum = (float)$leftValue + (float)$rightValue;

        return number_format($sum, $scale, '.', '');
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

    /**
     * Get the created at timestamp.
     */
    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAt(): void
    {
        if (null === $this->createdAt) {
            $this->createdAt = new DateTimeImmutable();
        }
    }

    public function getTaxRule(): ?TaxRule
    {
        return $this->taxRule;
    }

    public function setTaxRule(?TaxRule $taxRule): static
    {
        $this->taxRule = $taxRule;

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

    public function getLanguage(): ?Language
    {
        return $this->language;
    }

    public function setLanguage(?Language $language): static
    {
        $this->language = $language;

        return $this;
    }

    public function getUnitOfmeasurement(): ?string
    {
        return $this->unitOfmeasurement;
    }

    public function setUnitOfmeasurement(?string $unitOfmeasurement): static
    {
        $this->unitOfmeasurement = $unitOfmeasurement;

        return $this;
    }

    public function getWithoutDiscountPrice(): ?string
    {
        return $this->without_discount_price;
    }

    public function setWithoutDiscountPrice(?string $without_discount_price): static
    {
        $this->without_discount_price = $without_discount_price;

        return $this;
    }

    public function getAdditionalDiscount(): ?string
    {
        return $this->additional_discount;
    }

    public function setAdditionalDiscount(?string $additional_discount): static
    {
        $this->additional_discount = $additional_discount;

        return $this;
    }

    public function getTotalDiscountAmount(): ?string
    {
        return $this->total_discount_amount;
    }

    public function setTotalDiscountAmount(?string $total_discount_amount): static
    {
        $this->total_discount_amount = $total_discount_amount;

        return $this;
    }

    public function getTotalDiscountPercentage(): ?string
    {
        return $this->total_discount_percentage;
    }

    public function setTotalDiscountPercentage(?string $total_discount_percentage): static
    {
        $this->total_discount_percentage = $total_discount_percentage;

        return $this;
    }

    public function getTotalTaxExcluded(): ?string
    {
        return $this->total_tax_excluded;
    }

    public function setTotalTaxExcluded(?string $total_tax_excluded): static
    {
        $this->total_tax_excluded = $total_tax_excluded;

        return $this;
    }

    public function getShippingPrice(): ?string
    {
        return $this->shipping_price;
    }

    public function setShippingPrice(?string $shipping_price): static
    {
        $this->shipping_price = $shipping_price;

        return $this;
    }

    public function getTax(): ?string
    {
        return $this->tax;
    }

    public function setTax(?string $tax): static
    {
        $this->tax = $tax;

        return $this;
    }

    public function getTotalTaxIncluded(): ?string
    {
        return $this->total_tax_included;
    }

    public function setTotalTaxIncluded(?string $total_tax_included): static
    {
        $this->total_tax_included = $total_tax_included;

        return $this;
    }

    public function isQuoteSentToCustomer(): ?bool
    {
        return $this->quote_sent_to_customer;
    }

    public function getSentAt(): ?DateTimeImmutable
    {
        return $this->sentAt;
    }

    public function setSentAt(?DateTimeImmutable $sentAt): static
    {
        $this->sentAt = $sentAt;

        return $this;
    }

    public function isTransformedIntoAnOrder(): ?bool
    {
        return $this->transformed_into_an_order;
    }

    public function isArchived(): ?bool
    {
        return $this->archived;
    }

    public function getQualificationMessage(): ?string
    {
        return $this->qualification_message;
    }

    public function setQualificationMessage(?string $qualification_message): static
    {
        $this->qualification_message = $qualification_message;

        return $this;
    }

    public function getConversion(): ?Conversion
    {
        return $this->conversion;
    }

    public function setConversion(?Conversion $conversion): static
    {
        $this->conversion = $conversion;

        return $this;
    }

    public function getDeliveryAddress(): ?Address
    {
        return $this->deliveryAddress;
    }

    public function setDeliveryAddress(?Address $deliveryAddress): static
    {
        $this->deliveryAddress = $deliveryAddress;

        return $this;
    }

    public function getInvoiceAddress(): ?Address
    {
        return $this->invoiceAddress;
    }

    public function setInvoiceAddress(?Address $invoiceAddress): static
    {
        $this->invoiceAddress = $invoiceAddress;

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

    public function getTransportCondition(): ?TransportCondition
    {
        return $this->transportCondition;
    }

    public function setTransportCondition(?TransportCondition $transportCondition): static
    {
        $this->transportCondition = $transportCondition;

        return $this;
    }

    public function getOtherTva(): ?string
    {
        return $this->otherTva;
    }

    public function setOtherTva(?string $otherTva): static
    {
        $this->otherTva = $otherTva;

        return $this;
    }

    public function getCumulatedDiscountAmount(): ?string
    {
        return $this->cumulatedDiscountAmount;
    }

    public function setCumulatedDiscountAmount(?string $cumulatedDiscountAmount): static
    {
        $this->cumulatedDiscountAmount = $cumulatedDiscountAmount;

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

    public function getWeight(): ?string
    {
        return $this->weight;
    }

    public function setWeight(?string $weight): Quote
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * @return Collection<int, QuoteDetail>
     */
    public function getQuoteDetails(): Collection
    {
        return $this->quoteDetails;
    }

}
