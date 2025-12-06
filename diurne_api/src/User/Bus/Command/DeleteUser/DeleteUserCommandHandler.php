<?php

declare(strict_types=1);

namespace App\User\Bus\Command\DeleteUser;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Contact\Entity\ContactInformationSheet;
use App\User\Entity\User;
use App\User\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

final readonly class DeleteUserCommandHandler implements CommandHandler
{
    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(DeleteUserCommand $command): DeleteUserResponse
    {
        $user = $this->userRepository->findById($command->userId);
        if (!$user instanceof User) {
            throw new ResourceNotFoundException();
        }

        $this->entityManager
            ->createQueryBuilder()
            ->update(ContactInformationSheet::class, 'contactInformationSheet')
            ->set('contactInformationSheet.user', ':nullUser')
            ->where('contactInformationSheet.user = :user')
            ->setParameter('nullUser', null)
            ->setParameter('user', $user)
            ->getQuery()
            ->execute();

        $id = (string) $user->getId();
        $email = (string) $user->getEmail();
        $this->userRepository->delete($user);
        $this->userRepository->flush();

        return new DeleteUserResponse($id, $email);
    }
}
