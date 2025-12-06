<?php

declare(strict_types=1);

namespace App\User\Repository;

use App\Contremarque\Entity\CarpetDesignOrder;
use App\Common\Repository\BaseRepository;
use App\User\Entity\User;

/**
 * Interface for the user repository.
 * Defines the standard operations to be performed on User entities.
 */
interface UserRepository extends BaseRepository
{
    public function findById(int $id): ?User;

    public function findByEmail(string $email): ?User;

    public function save(User $user): void;

    public function delete(User $user): void;

    public function findAll(): array;

    public function userCanDo(User $user, $permission);

    public function findAvailableDesigner(CarpetDesignOrder $carpetDesignOrder);
}
