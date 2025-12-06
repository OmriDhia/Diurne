<?php

namespace App\Setting\DTO;

use App\Common\DTO\BaseDto;
use Symfony\Component\Validator\Constraints as Assert;

class CreateImageTypeRequestDto extends BaseDto
{
    #[Assert\NotBlank(message: 'Name is required')]
    #[Assert\Type('string', message: 'Name must be a string')]
    #[Assert\Length(max: 50, maxMessage: 'Name must be at most 50 characters')]
    public string $name;

    #[Assert\Type('string', message: 'Description must be a string')]
    #[Assert\Length(max: 255, maxMessage: 'Description must be at most 255 characters')]
    public ?string $description = null;

    #[Assert\Type('string', message: 'Category must be a string')]
    #[Assert\Length(max: 255, maxMessage: 'Category must be at most 255 characters')]
    public ?string $category = null;
}
