<?php

declare(strict_types=1);

namespace App\Contact\Repository;

use App\Common\Repository\BaseRepository;

interface AddressRepository extends BaseRepository
{
    public function getDeliveryAddress($customer);
    public function getRandomAddress($customer);
    public function getInvoiceAddress($customer);
}
