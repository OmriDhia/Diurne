<?php

declare(strict_types=1);

namespace App\Contremarque\DTO;

use App\Contremarque\DTO\Assert\NotNull;
use App\Contremarque\DTO\Assert\Type;
use App\Contremarque\DTO\Assert\Range;

class LayerDetailDto
{
    #[NotNull(message: 'The techColorId cannot be null.')]
    #[Type(type: 'integer', message: 'The techColorId must be an integer.')]
    public int $threadId;

    #[NotNull(message: 'The color_id cannot be null.')]
    #[Type(type: 'integer', message: 'The color_id must be an integer.')]
    public int $color_id;

    #[NotNull(message: 'The material_id cannot be null.')]
    #[Type(type: 'integer', message: 'The material_id must be an integer.')]
    public int $material_id;

    #[NotNull(message: 'The pourcentage cannot be null.')]
    #[Range(
        min: 0,
        max: 100,
        notInRangeMessage: 'The pourcentage must be between {{ min }} and {{ max }}.'
    )]
    public float $pourcentage;
}
