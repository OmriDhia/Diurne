<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Query\MobileApp\User;

use App\Common\Bus\Query\QueryHandler;
use App\MobileAppApi\Entity\UserMobileApp;
use App\MobileAppApi\Repository\UserMobileAppRepository;

final class GetUsersMobileAppHandler implements QueryHandler
{   
    public function __construct(
        private readonly UserMobileAppRepository $userRepository
    ) {}

    public function __invoke(GetUsersMobileAppQuery $query): UserListMobileAppResponse
    {
        $users = $this->userRepository->findAll();
        $dtos = array_map(fn($u) => $this->mapUser($u), $users);
        return new UserListMobileAppResponse($dtos);
    }

    private function mapUser(UserMobileApp $user): UserMobileAppResponse
    {
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
