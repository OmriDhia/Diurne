<?php

// src/App/Setting/DTO/CreateCollectionGroupRequestDto.php

namespace App\Setting\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreateCollectionGroupRequestDto
{
    #[Assert\NotBlank(message: 'Group number cannot be empty.')]
    #[Assert\Type(type: 'integer', message: 'Group number must be an integer.')]
    public int $group_number;
}
