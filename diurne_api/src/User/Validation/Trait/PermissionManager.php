<?php

declare(strict_types=1);

namespace App\User\Validation\Trait;

use DateTime;
use App\User\Entity\User;
use App\User\Repository\PermissionRepository;

trait PermissionManager
{
    public function __construct(
        private PermissionRepository $permissionRepository
    ) {
    }

    private ?array $cachedPermissions = null;
    private ?DateTime $cacheExpiresAt = null;

    // Adjusted method signature to accept User entity as parameter
    public function hasPermission(User $user, string $permission): bool
    {
        $this->updatePermissionsCache($user); // Pass the User entity to updatePermissionsCache
        if (is_numeric($permission)) {
            $permissionObject = $this->permissionRepository->find((int) $permission);
            $permission = $permissionObject->getName();
        }

        return in_array($permission, $this->cachedPermissions);
    }

    // Adjusted method signature to accept User entity as parameter
    private function updatePermissionsCache(User $user): void
    {
        if (!$this->cachedPermissions || !$this->cacheExpiresAt || $this->cacheExpiresAt < new DateTime()) {
            $this->cachedPermissions = $this->getPermissions($user); // Pass the User entity to getPermissions
            $this->cacheExpiresAt = new DateTime('+1 hour'); // Adjust cache expiration as needed
        }
    }

    // Adjusted method signature to accept User entity as parameter
    protected function getPermissions(User $user): array
    {
        $permissions = [];
        $profile = $user->getProfile(); // Use the passed User entity to retrieve the profile
        if ($profile) {
            foreach ($profile->getPermission() as $permission) {
                $permissions[] = $permission->getName(); // Assuming Permission entity has a getName() method
            }
        }

        return $permissions;
    }
}
