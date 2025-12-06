<?php

declare(strict_types=1);

namespace App\Contremarque\Repository;

use App\Common\Repository\BaseRepository;
use App\Contremarque\Entity\Attachment;

interface AttachmentRepository extends BaseRepository
{
    public function delete(Attachment $attachment): void;

}
