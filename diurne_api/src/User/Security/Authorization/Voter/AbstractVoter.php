<?php

namespace App\User\Security\Authorization\Voter;

use App\User\Entity\User;
use App\User\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

abstract class AbstractVoter extends Voter
{
    public function __construct(
        protected RequestStack $requestStack,
        protected UserRepository $userRepository,
    ) {}

    abstract protected function supports(string $attribute, mixed $subject): bool;

    abstract protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool;

    protected function canView(User $user, mixed $subject): bool
    {
        // if they can edit, they can view
        if ($this->canEdit($user, $subject) || $this->canCreate($user, $subject) || $this->canDelete($user, $subject)) {
            return true;
        }

        return $this->userRepository->userCanDo($user, 'read ' . (string)$subject);
    }

    protected function canEdit(User $user, mixed $subject): bool
    {
        return $this->userRepository->userCanDo($user, 'update ' . (string)$subject);
    }

    protected function canCreate(User $user, mixed $subject): bool
    {
        return $this->userRepository->userCanDo($user, 'create ' . (string)$subject);
    }

    protected function canDelete(User $user, mixed $subject): bool
    {
        return $this->userRepository->userCanDo($user, 'delete ' . $subject);
    }

    protected function canValidate(User $user, mixed $subject): bool
    {
        return $this->userRepository->userCanDo($user, 'validate ' . (string)$subject);
    }

    protected function canAssign(User $user, mixed $subject): bool
    {
        return $this->userRepository->userCanDo($user, 'assign ' . (string)$subject);
    }

    public function vote(TokenInterface $token, mixed $subject, array $attributes): int
    {
        foreach ($attributes as $attribute) {
            if (!$this->supports($attribute, $subject)) {
                continue;
            }

            if ($this->voteOnAttribute($attribute, $subject, $token)) {
                return self::ACCESS_GRANTED;
            }
        }

        return self::ACCESS_ABSTAIN;
    }
}
