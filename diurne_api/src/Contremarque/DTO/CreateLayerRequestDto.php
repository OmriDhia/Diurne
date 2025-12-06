<?php

declare(strict_types=1);

namespace App\Contremarque\DTO;

class CreateLayerRequestDto
{
    public int $layerNumber;
    public string $remarque;

    /**
     * @var LayerDetailDto[]|null
     */
    public ?array $layer_details = [];
}
