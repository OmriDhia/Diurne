<?php

namespace App\Invoices\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class GetSupplierInvoiceListRequestDto
{
    public function __construct(
        public ?string $invoiceNumber = null,
        public ?int    $authorId = null,
        #[Assert\PositiveOrZero]
        public int     $page = 1,
        #[Assert\Positive]
        public int     $itemsPerPage = 25,
        #[Assert\Range(min: 1, max: 100)]
        public ?int    $limit = null,
        public ?string $orderBy = null,
        public ?string $orderWay = null,
        public ?string $rn = null,
        // date filters in YYYY-MM-DD format (optional)
        public ?string $dateFrom = null,
        public ?string $dateTo = null
    )
    {
        if (null !== $this->limit) {
            $this->itemsPerPage = $this->limit;
        }
        // Normalize orderWay to uppercase if provided
        if (null !== $this->orderWay) {
            $this->orderWay = strtoupper($this->orderWay);
        }
    }
}
