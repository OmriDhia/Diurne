<?php

namespace App\Setting\Repository;

use App\Common\Repository\BaseRepository;
use App\Setting\Entity\AttachmentType;

interface AttachmentTypeRepository extends BaseRepository
{
    public function save(AttachmentType $collectionGroup): void;
}
