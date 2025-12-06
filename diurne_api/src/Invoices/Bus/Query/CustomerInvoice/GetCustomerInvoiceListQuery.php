<?php

namespace App\Invoices\Bus\Query\CustomerInvoice;

use App\Common\Bus\Query\Query;

class GetCustomerInvoiceListQuery implements Query
{
    /**
     * @param string|null $invoiceNumber
     * @param string|null $contremarque
     * @param string|null $fromDate
     * @param string|null $toDate
     * @param bool|null $cleared
     * @param int|null $page
     * @param int|null $itemsPerPage
     */
    public function __construct(
        public ?string $invoiceNumber = null,
        public ?string $contremarque = null,
        public ?string $fromDate = null,
        public ?string $toDate = null,
        public ?bool   $cleared = null,
        public ?int    $page = 1,
        public ?int    $itemsPerPage = 25
    )
    {
    }
}
