<?php

declare(strict_types=1);

namespace App\Contremarque\DTO\Image;

final readonly class GetImagesQueryDTO
{
    public function __construct(
        public ?int $idContremarque = null,
        public ?int $idDi = null,
        public ?int $idLocation = null
    ) {}
}
