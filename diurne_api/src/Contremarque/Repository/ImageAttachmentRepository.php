<?php

declare(strict_types=1);

namespace App\Contremarque\Repository;

use App\Common\Repository\BaseRepository;
use App\Contremarque\Entity\Image;

interface ImageAttachmentRepository extends BaseRepository
{
    public function findVignette($order, $location = false);

    public function findByType($order, string $type, $location = null);

    public function deleteByAttachment($image): void;

    public function removeByImage(Image $image): void;
}
