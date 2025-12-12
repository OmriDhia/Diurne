<?php

declare(strict_types=1);

namespace App\MobileAppApi\DTO\User;

use Symfony\Component\Validator\Constraints as Assert;

final class UpdateUserMobileAppRequestDto
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly bool $isActive,
        public readonly int $permissionId,
        public readonly ?string $picture = null,
        // Password update usually handled separately, or optional here. 
        // Following existing controller logic which didn't update password in update method explicitly in the provided snippet?
        // Wait, original snippet: $command = new UpdateUserMobileAppCommand($id, $command->name, ..., $command->picture); 
        // It does NOT seem to include password in update.
    ) {
    }
}
