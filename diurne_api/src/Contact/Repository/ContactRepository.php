<?php

declare(strict_types=1);

namespace App\Contact\Repository;

use App\Common\Repository\BaseRepository;
use App\User\Entity\User;

interface ContactRepository extends BaseRepository
{
    public function findOneByCode($code);

    public function findOneByUser(User $user);
}
