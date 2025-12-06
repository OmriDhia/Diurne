<?php

declare(strict_types=1);

namespace App\Contremarque\DTO\OrderPayment;

use Symfony\Component\Validator\Constraints as Assert;

class GetAllOrderPaymentRequestDto
{
    public function __construct(
        #[Assert\Positive]
        public ?int    $page = 1,

        #[Assert\Range(min: 1, max: 100)]
        public ?int    $itemsPerPage = 10,

        // allow 'limit' as an alias for itemsPerPage in query string
        #[Assert\Range(min: 1, max: 100)]
        public ?int    $limit = null,

        #[Assert\Type('string')]
        public ?string $customer = null,

        #[Assert\Type('string')]
        public ?string $commercial = null,

        #[Assert\Positive]
        public ?int    $currency = null,

        #[Assert\Type('numeric')]
        #[Assert\PositiveOrZero]
        public ?float  $minPaymentAmount = null,

        #[Assert\Type('numeric')]
        #[Assert\PositiveOrZero]
        #[Assert\GreaterThanOrEqual(propertyPath: 'minPaymentAmount')]
        public ?float  $maxPaymentAmount = null,
        #[Assert\Type('bool')]
        public ?bool   $hasNoChilds = null,

        // filter by carpet order id
        #[Assert\Positive]
        public ?int    $carpetOrderId = null,

        // Accept both snake_case and common camelCase aliases for orderBy
        #[Assert\Choice([
            'id', 'created_at', 'date_of_receipt', 'payment_amount_ht', 'customer', 'commercial',
            // camelCase aliases
            'createdAt', 'dateOfReceipt', 'paymentAmountHt'
        ])]
        public ?string $orderBy = null,

        // Accept lowercase or uppercase for order direction
        #[Assert\Choice(['ASC', 'DESC', 'asc', 'desc'])]
        public ?string $orderWay = 'DESC'
    )
    {
        // If a 'limit' param was provided, prefer it over itemsPerPage
        if (null !== $this->limit) {
            $this->itemsPerPage = $this->limit;
        }
    }
}