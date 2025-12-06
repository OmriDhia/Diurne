<?php

namespace App\Setting\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateCarpetCollectionDto
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

    public ?int $author_id = null;
}
