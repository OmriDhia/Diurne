<?php

namespace App\User\Security\Authorization\Voter;

use App\User\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class UserVoter extends AbstractVoter
{
    // these strings are just invented: you can use anything
    public const VIEW = 'read';
    public const CREATE = 'create';
    public const EDIT = 'update';
    public const DELETE = 'delete';
    public const VALIDATE = 'validate';
    public const ASSIGN = 'assign';

    /**
     * @param mixed|mixed $subject
     */
    protected function supports(string $attribute, mixed $subject): bool
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::VIEW, self::EDIT, self::CREATE, self::DELETE, self::VALIDATE])) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $session = $this->requestStack->getSession();
        $user = $session->get('user');

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        return match ($attribute) {
            self::VIEW => $this->canView($user, $subject),
            self::EDIT => $this->canEdit($user, $subject),
            self::CREATE => $this->canCreate($user, $subject),
            self::DELETE => $this->canDelete($user, $subject),
            self::VALIDATE => $this->canValidate($user, $subject),
            self::ASSIGN => $this->canAssign($user, $subject),
            default => false,
        };
    }
}
