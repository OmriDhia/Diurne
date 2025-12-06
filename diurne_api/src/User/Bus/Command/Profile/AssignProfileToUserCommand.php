<?php

declare(strict_types=1);

namespace App\User\Bus\Command\Profile;

use App\Common\Bus\Command\Command;

class AssignProfileToUserCommand implements Command
{
    /**
     * @var string
     */
    private $email;

    /**
     * @var int
     */
    private $profileId;

    public function __construct($email, $profileId)
    {
        $this->setUserEmail($email);
        $this->setProfileId($profileId);
    }

    public function setProfileId(int $profileId): AssignProfileToUserCommand
    {
        $this->profileId = $profileId;

        return $this;
    }

    public function getProfileId(): int
    {
        return $this->profileId;
    }

    public function setUserEmail(string $email): AssignProfileToUserCommand
    {
        $this->email = $email;

        return $this;
    }

    public function getUserEmail(): string
    {
        return $this->email;
    }
}
