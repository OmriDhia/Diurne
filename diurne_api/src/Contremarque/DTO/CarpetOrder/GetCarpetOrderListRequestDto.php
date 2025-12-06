<?php

namespace App\Contremarque\DTO\CarpetOrder;

class GetCarpetOrderListRequestDto
{
    /**
     * @param string|null $reference
     * @param string|null $devis
     * @param string|null $originalQuoteReference
     * @param string|null $contremarque
     * @param int|null $contremarqueId
     * @param string|null $customer
     * @param string|null $commercial
     * @param string|null $creationDate
     * @param int|null $page
     * @param int|null $itemsPerPage
     * @param string|null $orderBy
     * @param string|null $orderWay
     */
    public function __construct(
        public ?string $reference = null,
        public ?string $devis = null,
        public ?string $originalQuoteReference = null,
        public ?string $contremarque = null,
        public ?int    $contremarqueId = null,
        public ?string $customer = null,
        public ?string $commercial = null,
        public ?string $creationDate = null,
        public ?int    $page = 1,
        public ?int    $itemsPerPage = 50,
        public ?string $orderBy = null,
        public ?string $orderWay = 'DESC'
    )
    {
    }
}