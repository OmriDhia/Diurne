<?php

namespace App\Setting\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateMaterialPriceRequestDto
{
    #[Assert\NotBlank(message: 'Material ID is required')]
    #[Assert\Type('integer', message: 'Material name must be an integer')]
    public int $materialId;

    #[Assert\Type('float', message: 'Public price must be a float')]
    #[Assert\GreaterThanOrEqual(value: 0, message: 'Public price must be no less than zero')]
    public ?float $publicPrice = null;

    #[Assert\Type('float', message: 'Big project price must be a float')]
    #[Assert\GreaterThanOrEqual(value: 0, message: 'Big project price must be no less than zero')]
    public ?float $bigProjectPrice = null;
}
