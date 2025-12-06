<?php

// src/Setting/DTO/CreateCarpetCollectionDto.php

namespace App\Setting\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreateCarpetCollectionDto
{
    #[Assert\NotBlank(message: 'Reference cannot be empty.')]
    #[Assert\Length(max: 50, maxMessage: 'Reference cannot exceed {{ limit }} characters.')]
    public string $reference;

    #[Assert\NotBlank(message: 'Code cannot be empty.')]
    #[Assert\Length(max: 50, maxMessage: 'Code cannot exceed {{ limit }} characters.')]
    public string $code;

    #[Assert\NotNull(message: 'Collection Group cannot be null.')]
    public int $collection_group_id;

    #[Assert\NotNull(message: 'Show Grid cannot be null.')]
    public bool $show_grid;

    public ?int $special_shape_id = null;

    public ?int $police_id = null;

    public ?string $image_name = null;

    #[Assert\Positive(message: 'Author must be a valid user id.')]
    public ?int $author_id = null;

    /**
     * @var CreateCarpetCollectionLangRequestDto[]
     */
    #[Assert\Valid]
    #[Assert\Count(min: 1, minMessage: 'At least one language entry must be provided.')]
    public array $languages;
}
