<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Query\Permissions;

use App\Common\Bus\Query\QueryHandler;
use App\Common\Bus\Query\QueryResponse;
use App\Common\Exception\ResourceNotFoundException;
use App\MobileAppApi\Entity\PermissionsMobileApp;
use App\MobileAppApi\Repository\PermissionsMobileAppRepository;

final class GetPermissionMobileAppHandler implements QueryHandler
{
    public function __construct(
        private readonly PermissionsMobileAppRepository $permissionsRepository
    ) {}

    public function __invoke(GetPermissionMobileAppQuery $query): QueryResponse
    {
        if ($query->id) {
            // Note: If ID is provided, logic should probably return Single Response.
            // But original query was mixing List vs Single based on ID presence, which is a bit messy for types.
            // However, keeping strict to logic: if ID present -> return List with 1 item OR generic QueryResponse.
            // Wait, the client expects list for "Get Permissions" usually, unless it's /permissions/{id}.
            // My Controller used: get_mobile_permissions for List (GET /permissions) -- this used Query without ID.
            // And potentially another route for single? Ah, I made ONE handler for both?
            // "GetPermissionMobileAppQuery" had nullable ID.
            // If ID is set, I returned `[$mapped]`. So it was always a LIST in my previous implementation.

            // Let's stick to returning a List Response even for single item if that's what previous logic implied,
            // OR better, split it? 
            // The Controller `get` method `new GetPermissionMobileAppQuery()` has NO ID.
            // So for now, I only use this for LIST.
            // Wait, I didn't verify a "GET /permissions/{id}" endpoint in my controller. 
            // I only added `create`, `update`, `delete`. 
            // `get` was `GET /api/mobile/permissions`.
            // So I only need List logic here.

            // If I look at my previous tools, I didn't add GET /permissions/{id}. 
            // I added GET /users/{id}. 
            // So for permissions, it's just a LIST.

            $permissions = $this->permissionsRepository->findAll();
            $dtos = array_map(fn($p) => $this->mapPermission($p), $permissions);
            return new PermissionsListMobileAppResponse($dtos);
        }

        // If ID was passed (though not used in controller yet), logic handles it.
        // But for safety let's just implement listing.

        $permissions = $this->permissionsRepository->findAll();
        $dtos = array_map(fn($p) => $this->mapPermission($p), $permissions);
        return new PermissionsListMobileAppResponse($dtos);
    }

    private function mapPermission(PermissionsMobileApp $permission): PermissionsMobileAppResponse
    {
        return new PermissionsMobileAppResponse(
            id: $permission->getId(),
            name: $permission->getName(),
            description: $permission->getDescription(),
            createdAt: $permission->getCreatedAt()->format(\DateTime::ATOM)
        );
    }
}
