<?php

declare(strict_types=1);

namespace App\User\Bus\Query\GetPermissions;

use App\Common\Bus\Query\QueryHandler;
use App\User\Repository\PermissionRepository;

/**
 * This class is responsible for handling the 'get profiles' query, retrieving.
 */
final readonly class GetPermissionsQueryHandler implements QueryHandler
{
    public function __construct(
        private PermissionRepository $permissionRepository
    ) {
    }

    public function __invoke(GetPermissionsQuery $query): GetPermissionsResponse
    {
        $permissions = $this->permissionRepository->findAll();

        $formattedPermissions = array_reduce($permissions, function ($carry, $permission) {
            $entity = $permission->getEntity();
            $carry[$entity][] = [
                'permission_id' => $permission->getId(),
                'name' => $permission->getName(),
            ];

            return $carry;
        }, []);

        return new GetPermissionsResponse(
            $formattedPermissions
        );
    }
}
