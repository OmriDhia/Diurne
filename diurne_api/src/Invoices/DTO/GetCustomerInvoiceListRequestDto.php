<?php

namespace App\Invoices\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class GetCustomerInvoiceListRequestDto
{
    public function __construct(
        public ?string $invoiceNumber = null,
        public ?string $contremarque = null,
        public ?string $fromDate = null,
        public ?string $toDate = null,
        public ?bool $cleared = null,
        #[Assert\PositiveOrZero]
        public int $page = 1,
        #[Assert\Positive]
        public int $itemsPerPage = 25
    ) {
    }
}
