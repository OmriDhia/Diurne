<?php

declare(strict_types=1);

namespace App\Contremarque\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateLayerRequestDto
{
    #[Assert\Type('integer')]
    #[Assert\GreaterThan(0)]
    public ?int $layerNumber = null;

    #[Assert\Type('string')]
    public ?string $remarque = null;

    /**
     * @var array<array{threadId: int, color_id: int, material_id: int, pourcentage: float}>
     */
    public ?array $layer_details = null;
}
