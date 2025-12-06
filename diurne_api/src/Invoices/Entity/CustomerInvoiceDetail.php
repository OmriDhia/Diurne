<?php

namespace App\Invoices\Entity;

use DateTime;
use DateTimeInterface;
use App\Invoices\Entity\CustomerInvoice;
use App\Contremarque\Entity\CarpetOrder\CarpetOrderDetail;
use App\Setting\Entity\CarpetCollection;
use App\Setting\Entity\Model as SettingModel;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: "customer_invoice_detail")]
class CustomerInvoiceDetail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: CustomerInvoice::class, inversedBy: "customerInvoiceDetails")]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?CustomerInvoice $customerInvoice = null;

    #[ORM\ManyToOne(targetEntity: CarpetOrderDetail::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?CarpetOrderDetail $carpetOrderDetail = null;

    #[ORM\Column(type: Types::STRING, length: 10, nullable: true)]
    private ?string $rn = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    private ?CarpetCollection $collection = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    private ?SettingModel $model = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $m2 = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $sqft = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $ht = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $ttc = null;

    #[ORM\Column(type: "string", nullable: true)]
    private ?string $refCommand = null;

    #[ORM\Column(type: "string", nullable: true)]
    private ?string $refQuote = null;

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

    public function getCustomerInvoice(): ?CustomerInvoice
    {
        return $this->customerInvoice;
    }

    public function setCustomerInvoice(?CustomerInvoice $customerInvoice): self
    {
        $this->customerInvoice = $customerInvoice;
        return $this;
    }

    public function getCarpetOrderDetail(): ?CarpetOrderDetail
    {
        return $this->carpetOrderDetail;
    }

    public function setCarpetOrderDetail(?CarpetOrderDetail $carpetOrderDetail): self
    {
        $this->carpetOrderDetail = $carpetOrderDetail;
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

    public function getCollection(): ?CarpetCollection
    {
        return $this->collection;
    }

    public function setCollection(?CarpetCollection $collection): self
    {
        $this->collection = $collection;
        return $this;
    }

    public function getModel(): ?SettingModel
    {
        return $this->model;
    }

    public function setModel(?SettingModel $model): self
    {
        $this->model = $model;
        return $this;
    }

    public function getM2(): ?string
    {
        return $this->m2;
    }

    public function setM2(?string $m2): self
    {
        $this->m2 = $m2;
        return $this;
    }

    public function getSqft(): ?string
    {
        return $this->sqft;
    }

    public function setSqft(?string $sqft): self
    {
        $this->sqft = $sqft;
        return $this;
    }

    public function getHt(): ?string
    {
        return $this->ht;
    }

    public function setHt(?string $ht): self
    {
        $this->ht = $ht;
        return $this;
    }

    public function getTtc(): ?string
    {
        return $this->ttc;
    }

    public function setTtc(?string $ttc): self
    {
        $this->ttc = $ttc;
        return $this;
    }

    public function getRefCommand(): ?string
    {
        return $this->refCommand;
    }

    public function setRefCommand(?string $refCommand): self
    {
        $this->refCommand = $refCommand;
        return $this;
    }

    public function getRefQuote(): ?string
    {
        return $this->refQuote;
    }

    public function setRefQuote(?string $refQuote): self
    {
        $this->refQuote = $refQuote;
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
            'customerInvoice' => $this->customerInvoice?->getId(),
            'carpetOrderDetail' => $this->carpetOrderDetail?->getId(),
            'carpetOrderData' => $this->carpetOrderDetail?->toArray(),
            'rn' => $this->rn,
            'collection' => $this->collection?->getId(),
            'model' => $this->model?->getId(),
            'm2' => $this->m2,
            'sqft' => $this->sqft,
            'ht' => $this->ht,
            'ttc' => $this->ttc,
            'refCommand' => $this->refCommand,
            'refQuote' => $this->refQuote,
            'cleared' => $this->cleared,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
            'deleted' => $this->deleted,
            'deletedAt' => $this->deletedAt,
        ];
    }
}
