<?php

declare(strict_types=1);

namespace App\User\Bus\Query\MobileApp\User;

use App\Common\Bus\Query\QueryHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\User\Entity\UserMobileApp;
use App\User\Repository\UserMobileAppRepository;

final class GetUserMobileAppHandler implements QueryHandler
{
    public function __construct(
        private readonly UserMobileAppRepository $userRepository
    ) {}

    public function __invoke(GetUserMobileAppQuery $query): UserMobileAppResponse
    {
        $user = $this->userRepository->find($query->id);

        if (!$user instanceof UserMobileApp) {
            throw new ResourceNotFoundException('User not found');
        }

        return new UserMobileAppResponse(
            id: $user->getId(),
            name: $user->getName(),
            email: $user->getEmail(),
            isActive: $user->isActive(),
            picture: $user->getPicture(),
            permission: [
                'id' => $user->getPermission()->getId(),
                'name' => $user->getPermission()->getName(),
            ],
            createdAt: $user->getCreatedAt()->format(\DateTime::ATOM)
        );
    }
}
