<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetImages;

use App\Common\Bus\Query\Query;
use App\Contremarque\DTO\Image\GetImagesQueryDTO;

final class GetImagesQuery implements Query
{
    public function __construct(public readonly GetImagesQueryDTO $dto) {}
}
