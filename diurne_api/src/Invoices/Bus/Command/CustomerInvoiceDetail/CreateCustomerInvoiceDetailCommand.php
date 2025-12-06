<?php

namespace App\Invoices\Bus\Command\CustomerInvoiceDetail;

use App\Common\Bus\Command\Command;

class CreateCustomerInvoiceDetailCommand implements Command
{
    public function __construct(
        public readonly int $customerInvoiceId,
        public readonly int $carpetOrderDetailId,
        public readonly bool $cleared,
        public readonly ?string $refCommand = null,
        public readonly ?string $refQuote = null,
        public readonly ?string $rn = null,
        public readonly ?int $collectionId = null,
        public readonly ?int $modelId = null,
        public readonly ?string $m2 = null,
        public readonly ?string $sqft = null,
        public readonly ?string $ht = null,
        public readonly ?string $ttc = null,
    ) {
    }

    public function getCustomerInvoiceId(): int
    {
        return $this->customerInvoiceId;
    }

    public function getCarpetOrderDetailId(): int
    {
        return $this->carpetOrderDetailId;
    }

    public function isCleared(): bool
    {
        return $this->cleared;
    }

    public function getRefCommand(): ?string
    {
        return $this->refCommand;
    }

    public function getRefQuote(): ?string
    {
        return $this->refQuote;
    }

    public function getRn(): ?string
    {
        return $this->rn;
    }

    public function getCollectionId(): ?int
    {
        return $this->collectionId;
    }

    public function getModelId(): ?int
    {
        return $this->modelId;
    }

    public function getM2(): ?string
    {
        return $this->m2;
    }

    public function getSqft(): ?string
    {
        return $this->sqft;
    }

    public function getHt(): ?string
    {
        return $this->ht;
    }

    public function getTtc(): ?string
    {
        return $this->ttc;
    }
}
