<?php

// src/Setting/DTO/CreateCarpetCollectionLangRequestDto.php

namespace App\Setting\DTO;

use Symfony\Component\Validator\Constraints as Assert;
use App\Common\Assert\LanguageExists;

class CreateCarpetCollectionLangRequestDto
{
    #[Assert\NotBlank]
    #[Assert\Type(type: 'string')]
    #[Assert\Length(max: 50)]
    public string $description;

    #[Assert\NotBlank]
    #[Assert\Type(type: 'integer')]
    #[LanguageExists] // Apply the custom validator
    public int $languageId;

    // Optional: Add other validation constraints if needed
}
