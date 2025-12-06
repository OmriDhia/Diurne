<?php

namespace App\Invoices\Bus\Query\SupplierInvoice;

use App\Common\Bus\Query\QueryResponse;

class GetSupplierInvoiceListResponse implements QueryResponse
{
    public function __construct(
        public array $invoices,
        public int $total,
        public int $page,
        public int $itemsPerPage
    ) {
    }

    public function toArray(): array
    {
        return [
            'data' => $this->invoices,
            'meta' => [
                'total' => $this->total,
                'page' => $this->page,
                'itemsPerPage' => $this->itemsPerPage,
                'pages' => (int)ceil($this->total / $this->itemsPerPage)
            ]
        ];
    }
}
