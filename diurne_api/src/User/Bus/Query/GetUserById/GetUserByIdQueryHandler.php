<?php

declare(strict_types=1);

namespace App\User\Bus\Query\GetUserById;

use App\Common\Bus\Query\QueryHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Common\Exception\ValidationException;
use App\User\Repository\UserRepository;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * This class is responsible for handling the 'get user by ID' query, retrieving
 * user information based on their ID.
 */
final readonly class GetUserByIdQueryHandler implements QueryHandler
{
    /**
     * Constructor with UserRepository injection.
     *
     * @param UserRepository $userRepository user repository interface
     */
    public function __construct(
        private UserRepository $userRepository
    )
    {
    }

    /**
     * Handles the 'get user by ID' query.
     *
     * @param GetUserByIdQuery $query the query object containing the user ID
     *
     * @return GetUserByIdResponse the response object with user details
     *
     * @throws ResourceNotFoundException thrown when the user is not found
     * @throws ValidationException
     */
    public function __invoke(GetUserByIdQuery $query): GetUserByIdResponse
    {
        /**
         * @var UserInterface
         */
        $user = $this->userRepository->findById($query->userId());
        if (null === $user) {
            throw new ResourceNotFoundException();
        }
        $menus = $this->userRepository->getAccessibleMenus($query->userId());

        return new GetUserByIdResponse(
            (string)$user->getId(),
            (string)((!empty($user->getGender()) && $user->getGender()->getName()) ?? null),
            (string)$user->getEmail(),
            (string)$user->getLastName(),
            (string)$user->getFirstName(),
            (string)($user->getProfile()->getName() ?? null),
            (string)($user->getProfile()->getId() ?? null),
            !empty($user->getProfile()) ? $user->getProfile()->getPermission()->map(fn($obj) => $obj->getName())->getValues() : [],
            !empty($menus) ? $menus : [],
            (bool)$user->isActive()

        );
    }
}
