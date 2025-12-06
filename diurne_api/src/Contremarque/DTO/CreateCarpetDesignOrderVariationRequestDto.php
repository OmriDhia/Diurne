<?php

namespace App\Contremarque\DTO;

class CreateCarpetDesignOrderVariationRequestDto
{
    public int $orderId;
    public ?string $variation = null;
}
