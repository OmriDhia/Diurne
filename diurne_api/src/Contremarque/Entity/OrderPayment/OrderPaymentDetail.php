<?php

namespace App\Contremarque\Entity\OrderPayment;

use App\Invoices\Entity\CustomerInvoice;
use App\Invoices\Entity\CustomerInvoiceDetail;
use DateTimeInterface;
use DateTime;
use App\Contremarque\Entity\Quote;
use App\Contremarque\Entity\QuoteDetail;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: "order_payment_detail")]
class OrderPaymentDetail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;


    #[ORM\ManyToOne(targetEntity: OrderPayment::class, inversedBy: "orderPaymentDetails")]
    #[ORM\JoinColumn(name: "order_paiement_id", referencedColumnName: "id", nullable: false)]
    private ?OrderPayment $orderPayment = null;

    #[ORM\ManyToOne(targetEntity: Quote::class, inversedBy: 'orderPaymentDetails')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
    private ?Quote $quote = null;

    #[ORM\ManyToOne(targetEntity: QuoteDetail::class, inversedBy: 'orderPaymentDetails')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
    private ?QuoteDetail $quoteDetail = null;
    #[ORM\ManyToOne(targetEntity: CustomerInvoice::class, inversedBy: 'orderPaymentDetails')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
    private ?CustomerInvoice $customerInvoice = null;
    #[ORM\ManyToOne(targetEntity: CustomerInvoiceDetail::class, inversedBy: 'orderPaymentDetails')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
    private ?CustomerInvoiceDetail $customerInvoiceDetail = null;

    #[ORM\Column(type: "string", nullable: true)]
    private ?string $commandNumber = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $orderInvoiceId = null;

    #[ORM\Column(type: "string", length: 10)]
    private string $rn;

    #[ORM\Column(type: "decimal", precision: 20, scale: 6, options: ["default" => "0"])]
    private string $distribution = "0";

    #[ORM\Column(type: "decimal", precision: 20, scale: 6, options: ["default" => "0"])]
    private string $allocatedAmountTtc = "0";

    #[ORM\Column(type: "decimal", precision: 20, scale: 6, options: ["default" => "0"])]
    private string $remainingAmountTtc = "0";

    #[ORM\Column(type: "decimal", precision: 20, scale: 6, options: ["default" => "0"])]
    private string $totalAmountTtc = "0";

    #[ORM\Column(type: "decimal", precision: 20, scale: 6, options: ["default" => "0"])]
    private string $tva = "0";

    #[ORM\Column(type: "decimal", precision: 20, scale: 6, options: ["default" => "0"])]
    private string $allocatedAmountHt = "0";

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


    public function isDeleted(): bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): self
    {
        $this->deleted = $deleted;
        return $this;
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new DateTime();
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

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
        error_log('onPrePersist triggered');
    }

    public function toarray(): array
    {
        return [
            'id' => $this->id,
            'orderPayment' => $this->orderPayment->getId(),
            'quote' => $this->quote?->getId(),
            'quoteDetail' => $this->quoteDetail?->getId(),
            'customerInvoice' => $this->customerInvoice?->getId(),
            'customerInvoiceDetail' => $this->customerInvoiceDetail?->getId(),
            'commandNumber' => $this->commandNumber,
            'orderInvoiceId' => $this->orderInvoiceId,
            'rn' => $this->rn,
            'distribution' => $this->distribution,
            'allocatedAmountTtc' => $this->allocatedAmountTtc,
            'remainingAmountTtc' => $this->remainingAmountTtc,
            'totalAmountTtc' => $this->totalAmountTtc,
            'tva' => $this->tva,
            'allocatedAmountHt' => $this->allocatedAmountHt,
            'cleared' => $this->cleared,
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

    public function getOrderPayment(): ?OrderPayment
    {
        return $this->orderPayment;
    }

    public function setOrderPayment(?OrderPayment $orderPayment): self
    {
        $this->orderPayment = $orderPayment;
        return $this;
    }

    public function getQuote(): ?Quote
    {
        return $this->quote;
    }

    public function setQuote(?Quote $quote): self
    {
        $this->quote = $quote;
        return $this;
    }

    public function getQuoteDetail(): ?QuoteDetail
    {
        return $this->quoteDetail;
    }

    public function setQuoteDetail(?QuoteDetail $quoteDetail): self
    {
        $this->quoteDetail = $quoteDetail;
        return $this;
    }

    public function getCustomerInvoice(): ?CustomerInvoice
    {
        return $this->customerInvoice;
    }

    public function setCustomerInvoice(?CustomerInvoice $invoice): self
    {
        $this->customerInvoice = $invoice;
        return $this;
    }

    public function getCustomerInvoiceDetail(): ?CustomerInvoiceDetail
    {
        return $this->customerInvoiceDetail;
    }

    public function setCustomerInvoiceDetail(?CustomerInvoiceDetail $detail): self
    {
        $this->customerInvoiceDetail = $detail;
        return $this;
    }

    public function getCommandNumber(): ?string
    {
        return $this->commandNumber;
    }

    public function setCommandNumber(?string $commandNumber): self
    {
        $this->commandNumber = $commandNumber;
        return $this;
    }

    public function getOrderInvoiceId(): ?int
    {
        return $this->orderInvoiceId;
    }

    public function setOrderInvoiceId(?int $orderInvoiceId): self
    {
        $this->orderInvoiceId = $orderInvoiceId;
        return $this;
    }

    public function getRn(): string
    {
        return $this->rn;
    }

    public function setRn(string $rn): self
    {
        $this->rn = $rn;
        return $this;
    }

    public function getDistribution(): string
    {
        return $this->distribution;
    }

    public function setDistribution(string $distribution): self
    {
        $this->distribution = $distribution;
        return $this;
    }

    public function getAllocatedAmountTtc(): string
    {
        return $this->allocatedAmountTtc;
    }

    public function setAllocatedAmountTtc(string $allocatedAmountTtc): self
    {
        $this->allocatedAmountTtc = $allocatedAmountTtc;
        return $this;
    }

    public function getRemainingAmountTtc(): string
    {
        return $this->remainingAmountTtc;
    }

    public function setRemainingAmountTtc(string $remainingAmountTtc): self
    {
        $this->remainingAmountTtc = $remainingAmountTtc;
        return $this;
    }

    public function getTotalAmountTtc(): string
    {
        return $this->totalAmountTtc;
    }

    public function setTotalAmountTtc(string $totalAmountTtc): self
    {
        $this->totalAmountTtc = $totalAmountTtc;
        return $this;
    }

    public function getTva(): string
    {
        return $this->tva;
    }

    public function setTva(string $tva): self
    {
        $this->tva = $tva;
        return $this;
    }

    public function getAllocatedAmountHt(): string
    {
        return $this->allocatedAmountHt;
    }

    public function setAllocatedAmountHt(string $allocatedAmountHt): self
    {
        $this->allocatedAmountHt = $allocatedAmountHt;
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
}
