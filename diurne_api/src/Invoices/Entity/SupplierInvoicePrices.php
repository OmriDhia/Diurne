<?php

namespace App\Invoices\Entity;

use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Invoices\Entity\SupplierInvoice;

#[ORM\Entity]
class SupplierInvoicePrices
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $amount_theoretical = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $amount_real = null;

    #[ORM\Column]
    private ?int $credit_nember = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $credit_date = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6)]
    private ?string $payment_real = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $payment_theoretical = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $payment_date = null;

    #[ORM\OneToOne(inversedBy: 'prices', targetEntity: SupplierInvoice::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?SupplierInvoice $supplierInvoice = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmountTheoretical(): ?string
    {
        return $this->amount_theoretical;
    }

    public function setAmountTheoretical(?string $amount_theoretical): self
    {
        $this->amount_theoretical = $amount_theoretical;
        return $this;
    }

    public function getAmountReal(): ?string
    {
        return $this->amount_real;
    }

    public function setAmountReal(?string $amount_real): self
    {
        $this->amount_real = $amount_real;
        return $this;
    }

    public function getCreditNember(): ?int
    {
        return $this->credit_nember;
    }

    public function setCreditNember(int $credit_nember): self
    {
        $this->credit_nember = $credit_nember;
        return $this;
    }

    public function getCreditDate(): ?DateTimeInterface
    {
        return $this->credit_date;
    }

    public function setCreditDate(DateTimeInterface $credit_date): self
    {
        $this->credit_date = $credit_date;
        return $this;
    }

    public function getPaymentReal(): ?string
    {
        return $this->payment_real;
    }

    public function setPaymentReal(string $payment_real): self
    {
        $this->payment_real = $payment_real;
        return $this;
    }

    public function getPaymentTheoretical(): ?string
    {
        return $this->payment_theoretical;
    }

    public function setPaymentTheoretical(?string $payment_theoretical): self
    {
        $this->payment_theoretical = $payment_theoretical;
        return $this;
    }

    public function getPaymentDate(): ?DateTimeInterface
    {
        return $this->payment_date;
    }

    public function setPaymentDate(DateTimeInterface $payment_date): self
    {
        $this->payment_date = $payment_date;
        return $this;
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

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'amount_theoretical' => $this->amount_theoretical,
            'amount_real' => $this->amount_real,
            'credit_nember' => $this->credit_nember,
            'credit_date' => $this->credit_date?->format('Y-m-d H:i:s'),
            'payment_real' => $this->payment_real,
            'payment_theoretical' => $this->payment_theoretical,
            'payment_date' => $this->payment_date?->format('Y-m-d H:i:s'),
            'supplier_invoice_id' => $this->supplierInvoice?->getId(),
        ];
    }
}

