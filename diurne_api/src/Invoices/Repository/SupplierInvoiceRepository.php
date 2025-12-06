<?php

namespace App\Invoices\Repository;

use App\Common\Repository\BaseRepository;

interface SupplierInvoiceRepository extends BaseRepository
{
    public function getNextInvoiceNumber(): string;
}
