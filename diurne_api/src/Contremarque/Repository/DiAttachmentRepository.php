<?php

declare(strict_types=1);

namespace App\Contremarque\Repository;

use App\Common\Repository\BaseRepository;

interface DiAttachmentRepository extends BaseRepository
{
    public function deleteByAttachment($image): void;
}
