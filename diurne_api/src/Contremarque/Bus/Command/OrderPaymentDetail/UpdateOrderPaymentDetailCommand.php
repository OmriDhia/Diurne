<?php

namespace App\Contremarque\Bus\Command\OrderPaymentDetail;

use App\Common\Bus\Command\Command;

class UpdateOrderPaymentDetailCommand implements Command
{
    public function __construct(
        public readonly ?int    $id,
        public readonly ?int    $orderPaymentId,
        public readonly ?int    $quoteId,
        public readonly ?int    $quoteDetailId,
        public readonly ?int    $customerInvoiceId,
        public readonly ?int    $customerInvoiceDetailId,
        public readonly ?string $commandNumber,
        public readonly ?int    $orderInvoiceId,
        public readonly ?string $rn,
        public readonly ?string $distribution,
        public readonly ?string $allocatedAmountTtc,
        public readonly ?string $remainingAmountTtc,
        public readonly ?string $totalAmountTtc,
        public readonly ?string $tva,
        public readonly ?string $allocatedAmountHt,
        public readonly ?bool   $cleared
    )
    {
    }}