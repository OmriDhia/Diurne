<?php

declare(strict_types=1);

namespace App\Contremarque\Repository;

use App\Common\Repository\BaseRepository;

interface CarpetDesignOrderAttachmentRepository extends BaseRepository
{
    public function deleteByAttachment($image): void;
}
