<?php

namespace App\Setting\Bus\Command\CollectionGroupPrice;

use App\Common\Bus\Command\Command;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateCollectionGroupPriceCommand implements Command
{
    public function __construct(
        #[Assert\NotBlank(message: 'ID cannot be empty.')]
        public readonly int $id,
        
        #[Assert\NotBlank(message: 'Collection Group ID cannot be empty.')]
        public readonly int $collection_group_id,

        #[Assert\NotBlank(message: 'Price cannot be empty.')]
        #[Assert\Positive(message: 'Price must be positive.')]
        public readonly String $price,

        #[Assert\NotBlank(message: 'Max Price cannot be empty.')]
        #[Assert\Positive(message: 'Max Price must be positive.')]
        public readonly String $price_max,

        #[Assert\NotBlank(message: 'Tarif Group ID cannot be empty.')]
        public readonly int $tarif_group_id
    ) {}
}
