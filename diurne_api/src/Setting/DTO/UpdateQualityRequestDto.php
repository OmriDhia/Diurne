<?php

namespace App\Setting\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateQualityRequestDto
{
    #[Assert\Type(type: 'string', message: 'Name must be a string.')]
    public ?string $name = null;

    #[Assert\Type(type: 'string', message: 'Weight must be a string.')]
    public ?string $weight = null;

    #[Assert\Type(type: 'string', message: 'Velvet standard must be a string.')]
    public ?string $velvet_standard = null;
    #[TypeAttribute(type: 'array', message: 'Description must be an array.')]
    #[ValidAttribute(message: 'Description are not valid.')]
    public ?array $description = null;
}
