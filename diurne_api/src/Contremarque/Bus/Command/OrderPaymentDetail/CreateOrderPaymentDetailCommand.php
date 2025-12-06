<?php

namespace App\Contremarque\Bus\Command\OrderPaymentDetail;

use App\Common\Bus\Command\Command;

class CreateOrderPaymentDetailCommand implements Command
{
    public function __construct(
        public readonly int     $orderPaymentId,
        public readonly ?int    $quoteId,
        public readonly ?int    $quoteDetailId,
        public readonly ?int    $customerInvoiceId,
        public readonly ?int    $customerInvoiceDetailId,
        public readonly ?string $commandNumber,
        public readonly ?int    $orderInvoiceId,
        public readonly string  $rn,
        public readonly string  $distribution,
        public readonly string  $allocatedAmountTtc,
        public readonly string  $remainingAmountTtc,
        public readonly string  $totalAmountTtc,
        public readonly string  $tva,
        public readonly string  $allocatedAmountHt,
        public readonly bool    $cleared
    )
    {
    }

    public function getOrderPaymentId(): int
    {
        return $this->orderPaymentId;
    }

    public function getQuoteId(): ?int
    {
        return $this->quoteId;
    }

    public function getQuoteDetailId(): ?int
    {
        return $this->quoteDetailId;
    }

    public function getCustomerInvoiceId(): ?int
    {
        return $this->customerInvoiceId;
    }

    public function getCustomerInvoiceDetailId(): ?int
    {
        return $this->customerInvoiceDetailId;
    }

    public function getCommandNumber(): ?string
    {
        return $this->commandNumber;
    }

    public function getOrderInvoiceId(): ?int
    {
        return $this->orderInvoiceId;
    }

    public function getRn(): string
    {
        return $this->rn;
    }

    public function getDistribution(): string
    {
        return $this->distribution;
    }

    public function getAllocatedAmountTtc(): string
    {
        return $this->allocatedAmountTtc;
    }

    public function getRemainingAmountTtc(): string
    {
        return $this->remainingAmountTtc;
    }

    public function getTotalAmountTtc(): string
    {
        return $this->totalAmountTtc;
    }

    public function getTva(): string
    {
        return $this->tva;
    }

    public function getAllocatedAmountHt(): string
    {
        return $this->allocatedAmountHt;
    }

    public function isCleared(): bool
    {
        return $this->cleared;
    }
}