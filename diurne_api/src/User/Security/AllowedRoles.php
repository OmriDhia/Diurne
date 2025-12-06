<?php

declare(strict_types=1);

namespace App\User\Security;

/**
 * This class defines a list of allowed roles within the system.
 */
class AllowedRoles
{
    /**
     * An array of allowed roles.
     */
    public const ROLES = ['ROLE_ADMIN', 'ROLE_USER'];

    /**
     * Get the list of allowed roles.
     *
     * @return string[] an array of allowed roles
     */
    public static function getRoles(): array
    {
        return self::ROLES;
    }
}
