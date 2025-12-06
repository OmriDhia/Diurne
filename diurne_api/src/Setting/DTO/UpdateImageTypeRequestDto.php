<?php

namespace App\Setting\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateImageTypeRequestDto
{
    #[Assert\NotBlank(message: 'Name is required')]
    #[Assert\Type('string', message: 'Name must be a string')]
    #[Assert\Length(max: 128, maxMessage: 'Name cannot exceed 128 characters')]
    public string $name;

    #[Assert\Type('string', message: 'Description must be a string')]
    #[Assert\Length(max: 255, maxMessage: 'Description cannot exceed 255 characters')]
    public ?string $description = null;

    #[Assert\Type('string', message: 'Category must be a string')]
    #[Assert\Length(max: 255, maxMessage: 'Category must be at most 255 characters')]
    public ?string $category = null;
}
