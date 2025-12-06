<?php

namespace App\Setting\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateCollectionGroupRequestDto
{
    // Add your validation constraints here
    #[Assert\NotBlank(message: 'collection_group_id is required')]
    #[Assert\Type('int', message: 'collection_group_id must be a string')]
    public int $collection_group_id;
}
