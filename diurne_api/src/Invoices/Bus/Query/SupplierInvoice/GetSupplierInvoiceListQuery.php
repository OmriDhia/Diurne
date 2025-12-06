<?php

namespace App\Invoices\Bus\Query\SupplierInvoice;

use App\Common\Bus\Query\Query;

class GetSupplierInvoiceListQuery implements Query
{
    public function __construct(
        public ?string $invoiceNumber = null,
        public ?int    $authorId = null,
        public ?int    $page = 1,
        public ?int    $itemsPerPage = 25,
        public ?string $orderBy = null,
        public ?string $orderWay = null,
        public ?string $rn = null,
        public ?string $dateFrom = null,
        public ?string $dateTo = null
    )
    {
    }
}
