<?php

declare(strict_types=1);

namespace App\Contact\Bus\Query\GetCommercials;

use App\Common\Bus\Query\QueryHandler;
use App\User\Repository\ProfileRepository;
use App\User\Repository\UserRepository;

final readonly class GetCommercialsQueryHandler implements QueryHandler
{
    public function __construct(
        private UserRepository    $userRepository,
        private ProfileRepository $profileRepository
    )
    {
    }

    public function __invoke(GetCommercialsQuery $query)
    {
        $profile = $this->profileRepository->findOneBY(['name' => 'Commercial']);
        $commercials = $this->userRepository->findBy([
            'profile' => $profile,
            'isActive' => true,
        ]);

        $formattedCommercials = array_map(fn($commercial) => [
            'user_id' => $commercial->getId(),
            'firstname' => $commercial->getFirstname(),
            'lastname' => $commercial->getLastname(),
            'email' => $commercial->getEmail(),
        ], $commercials);

        return new GetCommercialsResponse(
            $formattedCommercials
        );
    }
}
