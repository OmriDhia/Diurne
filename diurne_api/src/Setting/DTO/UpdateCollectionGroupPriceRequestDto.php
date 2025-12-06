<?php

namespace App\Setting\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateCollectionGroupPriceRequestDto
{
    #[Assert\NotBlank(message: 'Group id cannot be empty.')]
    #[Assert\Type(type: 'integer', message: 'Group number must be an integer.')]
    public int $collection_group_id;

    #[Assert\NotBlank(message: 'Price cannot be empty.')]
    #[Assert\Type(type: 'String', message: 'Price must be a integer.')]
    public String $price;

    #[Assert\Type(type: 'String', message: 'Maximum price must be a integer.')]
    public ?String $price_max = null;

    #[Assert\NotBlank(message: 'Tarif group ID cannot be empty.')]
    #[Assert\Type(type: 'integer', message: 'Tarif group ID must be an integer.')]
    public int $tarif_group_id;
}
