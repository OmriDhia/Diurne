<?php

declare(strict_types=1);

namespace App\User\Bus\Query\GetProfiles;

use App\Common\Bus\Query\QueryHandler;
use App\User\Repository\ProfileRepository;

/**
 * This class is responsible for handling the 'get profiles' query, retrieving.
 */
final readonly class GetProfilesQueryHandler implements QueryHandler
{
    /**
     * Constructor with UserRepository injection.
     *
     * @param ProfileRepository $profileRepository profile repository interface
     */
    public function __construct(
        private ProfileRepository $profileRepository
    ) {
    }

    public function __invoke(GetProfilesQuery $query): GetProfilesResponse
    {
        $profiles = $this->profileRepository->findAll();

        $formattedProfiles = array_map(fn($profile) => [
            'profile_id' => $profile->getId(),
            'name' => $profile->getName(),
        ], $profiles);

        return new GetProfilesResponse(
            $formattedProfiles
        );
    }
}
