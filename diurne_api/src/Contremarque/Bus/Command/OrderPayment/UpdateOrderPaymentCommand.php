<?php

namespace App\Contremarque\Bus\Command\OrderPayment;

use App\Common\Bus\Command\Command;

class UpdateOrderPaymentCommand implements Command
{
    public function __construct(
        public readonly int     $id,
        public readonly ?int    $paymentMethodId,
        public readonly ?int    $customerId,
        public readonly bool    $customerIdProvided,
        public readonly ?int    $commercialId,
        public readonly bool    $commercialIdProvided,
        public readonly ?int    $currencyId,
        public readonly ?int    $taxRuleId,
        public readonly ?string $accountLabel,
        public readonly ?string $transactionNumber,
        public readonly ?string $paymentAmountHt,
        public readonly ?string $paymentAmountTtc
    )
    {
    }
}