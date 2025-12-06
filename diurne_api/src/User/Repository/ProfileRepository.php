<?php

declare(strict_types=1);

namespace App\User\Repository;

use App\Common\Repository\BaseRepository;

/**
 * Interface for the user repository.
 * Defines the standard operations to be performed on User entities.
 */
interface ProfileRepository extends BaseRepository
{
    public function findOneByName(string $name);

}
