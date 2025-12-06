<?php

namespace App\Setting\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreateTransportConditionRequestDto
{
    // Add your validation constraints here
    #[Assert\NotBlank(message: 'Name is required')]
    #[Assert\Type('string', message: 'Name must be a string')]
    public string $name;

    /**
     * @var CreateTransportConditionLangRequestDto[]
     */
    #[Assert\Valid]
    #[Assert\Count(min: 1, minMessage: 'At least one language entry must be provided.')]
    public array $languages;
}
