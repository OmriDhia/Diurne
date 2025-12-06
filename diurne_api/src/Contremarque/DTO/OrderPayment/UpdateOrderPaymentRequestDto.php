<?php

namespace App\Contremarque\DTO\OrderPayment;

use App\Common\DTO\BaseDto;
use App\Contremarque\Bus\Command\OrderPayment\UpdateOrderPaymentCommand;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateOrderPaymentRequestDto extends BaseDto
{

    #[Assert\Type("int")]
    public ?int $paymentMethodId = null;

    #[Assert\AtLeastOneOf(constraints: [new Assert\Type('int'), new Assert\IsNull()])]
    private ?int $customerId = null;

    private bool $customerIdProvided = false;

    #[Assert\AtLeastOneOf(constraints: [new Assert\Type('int'), new Assert\IsNull()])]
    private ?int $commercialId = null;

    private bool $commercialIdProvided = false;

    #[Assert\Type("int")]
    public ?int $currencyId = null;

    #[Assert\Type("int")]
    public ?int $taxRuleId = null;

    public ?string $accountLabel = null;

    #[Assert\Length(max: 128)]
    public ?string $transactionNumber = null;

    #[Assert\Type("numeric")]
    public ?string $paymentAmountHt = null;

    #[Assert\Type("numeric")]
    public ?string $paymentAmountTtc = null;

    public function toUpdateOrderPaymentCommand(int $id): UpdateOrderPaymentCommand
    {
        return new UpdateOrderPaymentCommand(
            $id,
            $this->paymentMethodId,
            $this->getCustomerId(),
            $this->isCustomerIdProvided(),
            $this->getCommercialId(),
            $this->isCommercialIdProvided(),
            $this->currencyId,
            $this->taxRuleId,
            $this->accountLabel,
            $this->transactionNumber,
            $this->paymentAmountHt,
            $this->paymentAmountTtc
        );
    }

    public function setCustomerId(?int $customerId): void
    {
        $this->customerIdProvided = true;
        $this->customerId = $customerId;
    }

    public function getCustomerId(): ?int
    {
        return $this->customerId;
    }

    public function isCustomerIdProvided(): bool
    {
        return $this->customerIdProvided;
    }

    public function setCommercialId(?int $commercialId): void
    {
        $this->commercialIdProvided = true;
        $this->commercialId = $commercialId;
    }

    public function getCommercialId(): ?int
    {
        return $this->commercialId;
    }

    public function isCommercialIdProvided(): bool
    {
        return $this->commercialIdProvided;
    }
}
