<?php

namespace App\Setting\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateTransportConditionLangRequestDto
{
    // Add your validation constraints here
    #[Assert\NotBlank(message: 'transport_condition_lang_id is required')]
    #[Assert\Type('integer', message: 'transport_condition_lang_id must be a integer')]
    public int $transport_condition_lang_id;

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
