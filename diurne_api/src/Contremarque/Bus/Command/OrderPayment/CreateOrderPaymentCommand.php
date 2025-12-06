<?php

namespace App\Contremarque\Bus\Command\OrderPayment;

use DateTimeInterface;
use DateTimeImmutable;
use App\Common\Bus\Command\Command;

class CreateOrderPaymentCommand implements Command
{
    private ?DateTimeInterface $dateOfReceipt = null;

    public function __construct(
        public readonly int $paymentMethodId,
        public readonly ?int $customerId,
        public readonly ?int $commercialId,
        public readonly int $currencyId,
        public readonly int $taxRuleId,
        public readonly ?string $accountLabel,
        public readonly ?string $transactionNumber,
        public readonly string $paymentAmountHt,
        public readonly string $paymentAmountTtc,
    ) {
        $this->dateOfReceipt = new DateTimeImmutable();
    }

    public function getDateOfReceipt(): DateTimeInterface|null
    {
        return $this->dateOfReceipt;
    }

    public function getPaymentMethodId(): int
    {
        return $this->paymentMethodId;
    }

    public function getCustomerId(): ?int
    {
        return $this->customerId;
    }

    public function getCommercialId(): ?int
    {
        return $this->commercialId;
    }

    public function getCurrencyId(): int
    {
        return $this->currencyId;
    }

    public function getTaxRuleId(): int
    {
        return $this->taxRuleId;
    }

    public function getAccountLabel(): ?string
    {
        return $this->accountLabel;
    }

    public function getTransactionNumber(): ?string
    {
        return $this->transactionNumber;
    }

    public function getPaymentAmountHt(): string
    {
        return $this->paymentAmountHt;
    }

    public function getPaymentAmountTtc(): string
    {
        return $this->paymentAmountTtc;
    }
}
