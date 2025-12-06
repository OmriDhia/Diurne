<?php

// src/App/Setting/DTO/CreateCollectionGroupPriceRequestDto.php

namespace App\Setting\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreateCollectionGroupPriceRequestDto
{
    #[Assert\NotBlank(message: 'Group id cannot be empty.')]
    #[Assert\Type(type: 'integer', message: 'Group number must be an integer.')]
    public int $group_number;

    #[Assert\NotBlank(message: 'Price cannot be empty.')]
    #[Assert\Type(type: 'string', message: 'Price must be a string.')]
    public string $price;

    #[Assert\Type(type: 'string', message: 'Maximum price must be a string.')]
    public ?string $price_max = null;

    #[Assert\NotBlank(message: 'Tarif group ID cannot be empty.')]
    #[Assert\Type(type: 'integer', message: 'Tarif group ID must be an integer.')]
    public int $tarif_group_id;
}
