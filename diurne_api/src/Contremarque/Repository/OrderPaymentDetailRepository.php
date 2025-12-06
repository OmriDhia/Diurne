<?php

namespace App\Contremarque\Repository;

use App\Common\Repository\BaseRepository;

interface OrderPaymentDetailRepository extends BaseRepository
{
    public function softDeleteByOrderPayment(int $orderId): void;
}