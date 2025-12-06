<?php

declare(strict_types=1);

namespace App\User\DTO;

class GetUsersFilterDto
{
    public ?string $firstname = null;
    public ?string $lastname = null;
    public ?string $email = null;
    public ?string $profileId = null;
    public ?string $gender = null;
    public ?string $profiles = null;
    public ?bool $isActive = null;
}
