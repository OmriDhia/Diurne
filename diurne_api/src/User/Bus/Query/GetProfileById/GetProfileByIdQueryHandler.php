<?php

declare(strict_types=1);

namespace App\User\Bus\Query\GetProfileById;

use App\Common\Bus\Query\QueryHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Common\Exception\ValidationException;
use App\User\Entity\Profile;
use App\User\Repository\ProfileRepository;

/**
 * This class is responsible for handling the 'get profile by ID' query, retrieving
 * profile information based on their ID.
 */
final readonly class GetProfileByIdQueryHandler implements QueryHandler
{
    /**
     * Constructor with ProfileRepository injection.
     *
     * @param ProfileRepository $profileRepository profile repository interface
     */
    public function __construct(
        private ProfileRepository $profileRepository
    ) {
    }

    /**
     * Handles the 'get profile by ID' query.
     *
     * @param GetProfileByIdQuery $query the query object containing the profile ID
     *
     * @return GetProfileByIdResponse the response object with profile details
     *
     * @throws ResourceNotFoundException thrown when the profile is not found
     * @throws ValidationException
     */
    public function __invoke(GetProfileByIdQuery $query): GetProfileByIdResponse
    {
        /**
         * @var Profile
         */
        $profile = $this->profileRepository->find((int) $query->profileId());
        if (null === $profile) {
            throw new ResourceNotFoundException();
        }

        return new GetProfileByIdResponse(
            (string) $profile->getId(),
            (string) $profile->getName(),
            !empty($profile) ? $profile->getPermission()->map(fn($obj) => $obj->getName())->getValues() : []
        );
    }
}
