<?php

namespace App\Contremarque\Bus\Query\OrderPayment;

use App\Common\Bus\Query\Query;

final readonly class GetAllOrderPaymentQuery implements Query
{
    public function __construct(
        public ?int    $page = 1,
        public ?int    $itemsPerPage = 25,
        public ?string $customer = null,
        public ?string $commercial = null,
        public ?float  $minPaymentAmount = null,
        public ?float  $maxPaymentAmount = null,
        public ?int    $currency = null,
        // filter by related carpet order id
        public ?int    $carpetOrderId = null,
        public ?bool   $hasNoChilds = null,
        public ?string $orderBy = null,
        public ?string $orderWay = 'DESC'
    )
    {
    }

}