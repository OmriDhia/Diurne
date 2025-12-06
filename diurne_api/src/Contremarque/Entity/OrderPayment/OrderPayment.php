<?php

namespace App\Contremarque\Entity\OrderPayment;

use DateTimeInterface;
use DateTime;
use App\Contact\Entity\Customer;
use App\Setting\Entity\Currency;
use App\Setting\Entity\PaymentType;
use App\Setting\Entity\TaxRule;
use App\User\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: "order_payment")]
class OrderPayment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "datetime")]
    private ?DateTimeInterface $dateOfReceipt = null;

    #[ORM\ManyToOne(targetEntity: PaymentType::class)]
    #[ORM\JoinColumn(name: "payment_method_id", referencedColumnName: "id", nullable: false)]
    private ?PaymentType $paymentMethod = null;

    #[ORM\ManyToOne(targetEntity: Customer::class)]
    #[ORM\JoinColumn(name: "customer_id", referencedColumnName: "id", nullable: true, onDelete: "SET NULL")]
    private ?Customer $customer = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "commercial_id", referencedColumnName: "id", nullable: true, onDelete: "SET NULL")]
    private ?User $commercial = null;

    #[ORM\ManyToOne(targetEntity: Currency::class)]
    #[ORM\JoinColumn(name: "currency_id", referencedColumnName: "id", nullable: false)]
    private ?Currency $currency = null;

    #[ORM\ManyToOne(targetEntity: TaxRule::class)]
    #[ORM\JoinColumn(name: "tax_rule_id", referencedColumnName: "id", nullable: false)]
    private ?TaxRule $taxRule = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $accountLabel = null;

    #[ORM\Column(type: "string", length: 128, nullable: true)]
    private ?string $transactionNumber = null;

    #[ORM\Column(type: "decimal", precision: 20, scale: 6)]
    private string $paymentAmountHt;

    #[ORM\Column(type: "decimal", precision: 20, scale: 6)]
    private string $paymentAmountTtc;
    #[ORM\Column(type: "boolean", options: ["default" => false])]
    private bool $deleted = false;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?DateTimeInterface $deletedAt = null;
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private DateTimeInterface $createdAt;
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private DateTimeInterface $updatedAt;

    #[ORM\OneToMany(targetEntity: OrderPaymentDetail::class, mappedBy: "orderPayment", cascade: ["persist"])]
    private Collection $orderPaymentDetails;

    public function __construct()
    {
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
            $orderPaymentDetail->setOrderPayment($this);
        }
        return $this;
    }

    public function removeOrderPaymentDetail(OrderPaymentDetail $orderPaymentDetail): self
    {
        if ($this->orderPaymentDetails->removeElement($orderPaymentDetail)) {
            if ($orderPaymentDetail->getOrderPayment() === $this) {
                $orderPaymentDetail->setOrderPayment(null);
            }
        }
        return $this;
    }

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
        error_log('onPrePersist triggered');
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new DateTime();
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

    public function getDeletedAt(): ?DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?DateTimeInterface $deletedAt): self
    {
        $this->deletedAt = $deletedAt;
        return $this;
    }

    public function toarray(): array
    {
        return [
            'id' => $this->id,
            'dateOfReceipt' => $this->dateOfReceipt,
            'paymentMethod' => $this->paymentMethod->getLabel(),
            'customer' => $this->customer?->getId(),
            'commercial' => $this->commercial?->getId(),
            'currency' => $this->currency->getName(),
            'taxRule' => $this->taxRule->getTaxRate(),
            'accountLabel' => $this->accountLabel,
            'transactionNumber' => $this->transactionNumber,
            'paymentAmountHt' => $this->paymentAmountHt,
            'paymentAmountTtc' => $this->paymentAmountTtc,
            'orderPaymentDetails' => array_map(
                fn($detail) => $detail->toArray(),
                array_filter(
                    $this->orderPaymentDetails->toArray(),
                    fn($detail) => !$detail->isDeleted()
                )
            ),
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
            'deleted' => $this->deleted,
            'deletedAt' => $this->deletedAt,
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDateOfReceipt(): ?DateTimeInterface
    {
        return $this->dateOfReceipt;
    }

    public function setDateOfReceipt(DateTimeInterface $dateOfReceipt): self
    {
        $this->dateOfReceipt = $dateOfReceipt;
        return $this;
    }

    public function getPaymentMethod(): ?PaymentType
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(?PaymentType $paymentMethod): self
    {
        $this->paymentMethod = $paymentMethod;
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

    public function getCommercial(): ?User
    {
        return $this->commercial;
    }

    public function setCommercial(?User $commercial): self
    {
        $this->commercial = $commercial;
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

    public function getTaxRule(): ?TaxRule
    {
        return $this->taxRule;
    }

    public function setTaxRule(?TaxRule $taxRule): self
    {
        $this->taxRule = $taxRule;
        return $this;
    }

    public function getAccountLabel(): ?string
    {
        return $this->accountLabel;
    }

    public function setAccountLabel(?string $accountLabel): self
    {
        $this->accountLabel = $accountLabel;
        return $this;
    }

    public function getTransactionNumber(): ?string
    {
        return $this->transactionNumber;
    }

    public function setTransactionNumber(?string $transactionNumber): self
    {
        $this->transactionNumber = $transactionNumber;
        return $this;
    }

    public function getPaymentAmountHt(): string
    {
        return $this->paymentAmountHt;
    }

    public function setPaymentAmountHt(string $paymentAmountHt): self
    {
        $this->paymentAmountHt = $paymentAmountHt;
        return $this;
    }

    public function getPaymentAmountTtc(): string
    {
        return $this->paymentAmountTtc;
    }

    public function setPaymentAmountTtc(string $paymentAmountTtc): self
    {
        $this->paymentAmountTtc = $paymentAmountTtc;
        return $this;
    }
}