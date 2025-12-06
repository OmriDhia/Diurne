<?php

namespace App\Invoices\Bus\Command\CustomerInvoiceDetail;

use App\Common\Bus\Command\Command;

class UpdateCustomerInvoiceDetailCommand implements Command
{
    public function __construct(
        public readonly int $id,
        public readonly ?int $customerInvoiceId,
        public readonly ?int $carpetOrderDetailId,
        public readonly ?bool $cleared,
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
}
