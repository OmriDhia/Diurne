<?php

declare(strict_types=1);

namespace App\Contremarque\Repository;

use App\Common\Repository\BaseRepository;
use App\Contremarque\DTO\Image\GetImagesQueryDTO;

interface ImageRepository extends BaseRepository
{
    public function getNextImageNumber();

    public function deleteById(int $id): void;

    public function findByFilters(GetImagesQueryDTO $dto): array;
}
