<?php

namespace App\Setting\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreateTransportConditionLangRequestDto
{
    #[Assert\NotBlank(message: 'language label is required')]
    #[Assert\Type('string', message: 'language label must be a string')]
    public string $label;

    #[Assert\NotBlank(message: 'language Description is required')]
    #[Assert\Type('string', message: 'language Description must be a string')]
    public string $description;

    #[Assert\NotBlank(message: 'lang_id is required')]
    #[Assert\Type('integer', message: 'lang_id must be a integer')]
    public int $lang_id;
}
