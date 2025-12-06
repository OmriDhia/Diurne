<?php

declare(strict_types=1);

namespace App\Contact\Repository;

use App\Common\Repository\BaseRepository;
use App\Contremarque\Entity\CarpetDesignOrder;
use App\User\Entity\User;

interface CustomerRepository extends BaseRepository
{
    public function findCommercialByCarpetDesignOrder(CarpetDesignOrder $carpetDesignOrder): ?User;
    public function getContactName(int $customerId): string;
    public function getCustomerAddress(int $customerId, string $addressType): ?string;
}
