<?php

namespace App\Invoices\Repository;

use App\Common\Repository\BaseRepository;

interface CustomerInvoiceRepository extends BaseRepository
{
    public function getNextInvoiceNumber(): string;
}
