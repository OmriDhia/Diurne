<?php

namespace App\Setting\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateTransportConditionRequestDto
{
    // Add your validation constraints here
    #[Assert\NotBlank(message: 'name is required')]
    #[Assert\Type('string', message: 'name must be a string')]
    public string $name;

    /**
     * @var UpdateTransportConditionLangRequestDto[]
     */
    #[Assert\Valid]
    #[Assert\Count(min: 1, minMessage: 'At least one language entry must be provided.')]
    public array $languages;
}
