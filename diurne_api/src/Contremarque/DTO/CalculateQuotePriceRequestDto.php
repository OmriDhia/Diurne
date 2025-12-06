<?php

namespace App\Contremarque\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CalculateQuotePriceRequestDto
{
    public function __construct(
        #[Assert\Type('float')]
        #[Assert\PositiveOrZero(message: 'Additional discount must be greater than or equal to 0')]
        public float $additionalDiscount = 0.0,

        #[Assert\Type('float')]
        #[Assert\PositiveOrZero(message: 'Shipping price must be greater than or equal to 0')]
        public float $shippingPrice = 0.0
    ) {}
}
