<?php

declare(strict_types=1);

namespace App\Contremarque\DTO;

class UpdateCarpetDesignOrderRequestDto
{
    public ?int $location_id = null;
    public ?array $designer_assignments = [];
    public ?int $status_id = null;
    public ?string $modelName
        = null;
    public ?string $variation = null;
    public ?bool $jpeg = null;
    public ?bool $impression = null;
    public ?bool $impressionBarreDeLaine = null;
}
