<?php

declare(strict_types=1);

namespace App\User\Bus\Query\HasPermissionTo;

use App\User\Validation\Trait\PermissionManager;
use App\Common\Bus\Query\QueryHandler;
use App\User\Repository\PermissionRepository;
use App\User\Repository\UserRepository;

final class HasPermissionToQueryHandler implements QueryHandler
{
    use PermissionManager;

    public function __construct(
        private UserRepository $userRepository,
        private PermissionRepository $permissionRepository
    ) {
    }

    public function __invoke(HasPermissionToQuery $query): HasPermissionToResponse
    {
        $user = $this->userRepository->find((int) $query->getUserId());
        $hasPermissionTo = $this->hasPermission($user, $query->getPermissionId());

        return new HasPermissionToResponse(
            $hasPermissionTo
        );
    }
}
