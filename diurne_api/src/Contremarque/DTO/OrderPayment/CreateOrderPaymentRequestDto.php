<?php

namespace App\Contremarque\DTO\OrderPayment;

use Symfony\Component\Validator\Constraints as Assert;

class CreateOrderPaymentRequestDto
{
    #[Assert\NotNull(message: "Payment method ID is required")]
    #[Assert\Type("int")]
    public int $paymentMethodId;

    #[Assert\AtLeastOneOf(constraints: [new Assert\Type('int'), new Assert\IsNull()])]
    public ?int $customerId = null;

    #[Assert\AtLeastOneOf(constraints: [new Assert\Type('int'), new Assert\IsNull()])]
    public ?int $commercialId = null;

    #[Assert\NotNull(message: "Currency ID is required")]
    #[Assert\Type("int")]
    public int $currencyId;

    #[Assert\NotNull(message: "Tax rule ID is required")]
    #[Assert\Type("int")]
    public int $taxRuleId;

    public ?string $accountLabel = null;

    #[Assert\Length(max: 128)]
    public ?string $transactionNumber = null;

    #[Assert\NotNull(message: "Payment amount HT is required")]
    #[Assert\Type("numeric")]
    public string $paymentAmountHt;

    #[Assert\NotNull(message: "Payment amount TTC is required")]
    #[Assert\Type("numeric")]
    public string $paymentAmountTtc;


}
